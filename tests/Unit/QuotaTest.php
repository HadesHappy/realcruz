<?php

namespace Tests\Unit;

use Tests\TestCase;

use Acelle\Library\QuotaTracker;
use Acelle\Model\SendingServer;
use Acelle\Model\Subscription;
use Acelle\Model\EmailVerificationServer;
use Acelle\Model\Subscriber;
use Acelle\Model\Customer;
use Acelle\Model\Campaign;
use Acelle\Model\MailList;
use Acelle\Library\QuotaManager;
use Acelle\Library\Exception\QuotaExceeded;
use Acelle\Library\Exception\NoCreditsLeft;
use Acelle\Jobs\SendMessage;
use Acelle\Jobs\VerifySubscriber;
use Exception;
use File;
use Mockery;
use function Acelle\Helpers\withQuota;

class QuotaTest extends TestCase
{
    public function initServer()
    {
        $server = new SendingServer();
        $server->generateUid();
        $server->cleanupCreditsStorageFiles('send');
        $server->setCredits('send', 100);

        return $server;
    }

    public function initSubscription()
    {
        $subscription = new Subscription();
        $subscription->generateUid();
        $subscription->cleanupCreditsStorageFiles('send');
        $subscription->cleanupCreditsStorageFiles('verify');
        $subscription->setCredits('send', 100);
        return $subscription;
    }

    public function initEmailVerificationServer()
    {
        $server = new EmailVerificationServer();
        $server->generateUid();
        $server->cleanupCreditsStorageFiles('verify');
        $server->setCredits('verify', 100);

        return $server;
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_just_works()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 100);
        $use  = rand(3, 10);
        $server->setQuotaSettings($limit = 10000000, $unit = 'minute', 1);

        for ($i = 1; $i <= $use; $i++) {
            QuotaManager::with($server, 'send')->count();
        }

        $this->assertEquals($server->getRemainingCredits('send'), $credits - $use);
        $this->assertEquals($server->getCreditsUsed('send'), $use);
    }

    public function test_speed_limit_exceeded()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 100);
        $limitPerMinute = 4;
        $use = 10;

        $server->setQuotaSettings($limitPerMinute, $unit = 'minute', 1);

        $this->expectException(Exception::class);

        try {
            for ($i = 1; $i <= $use; $i++) {
                QuotaManager::with($server, 'send')->count();
            }
        } finally {
            $this->assertEquals($i, 5); // failed at the first try after limit is reached
        }
    }

    public function test_speed_quota_exceeded_with_callback()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 100);
        $limitPerMinute = 4;
        $use = 10;
        $server->setQuotaSettings($limitPerMinute, $unit = 'minute', 1);

        //$this->expectException(Exception::class);

        $quota = QuotaManager::with($server, 'send')->whenQuotaExceeded(function ($limit, $creditsUsed, $time) use (&$results) {
            $results = [ $limit, $creditsUsed, $time ];
        });

        $this->expectException(QuotaExceeded::class);

        try {
            for ($i = 1; $i <= $use; $i++) {
                $quota->count();
            }
        } finally {
            $this->assertEquals($results[0]['limit'], $limitPerMinute); // failed at the first try after limit is reached
            $this->assertEquals($i, 5); // exception thrown if limit exceeded
            $this->assertEquals($server->getCreditsUsed('send'), 4);
            $this->assertEquals($server->getRemainingCredits('send'), 96);
        }
    }

    public function test_server_no_credits_left()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 1);
        $use = 10;
        $server->setQuotaSettings(9999, $unit = 'minute', $base = 1);


        $quota = QuotaManager::with($server, 'send');

        $this->expectException(NoCreditsLeft::class);

        try {
            for ($i = 1; $i <= $use; $i++) {
                $quota->count();
            }
        } finally {
            $this->assertEquals($i, 2); // exception thrown if limit exceeded
            $this->assertEquals($server->getCreditsUsed('send'), 1);
            $this->assertEquals($server->getRemainingCredits('send'), 0);
        }
    }

    public function test_credit_exceeded()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 5);
        $use = 1000;

        $server->setQuotaSettings($limitsPerMinute = 10000, $unit = 'minute', 1);
        $this->expectException(NoCreditsLeft::class);

        try {
            for ($i = 1; $i <= $use; $i++) {
                QuotaManager::with($server, 'send')->count();
            }
        } finally {
            $this->assertEquals($i, $credits + 1); // failed at the first try after limit is reached
        }
    }

    public function test_speed_limit_reached()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 100);
        $limitPerMinute = 4;
        $use = 4;

        $server->setQuotaSettings($limitPerMinute, $unit = 'minute', 1);

        try {
            for ($i = 1; $i <= $use; $i++) {
                QuotaManager::with($server, 'send')->count();
            }
        } finally {
            $this->assertEquals($i, $use + 1); // failed at the first try after limit is reached
        }
    }

    public function test_credit_reached()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 5);
        $use = 5;

        $server->setQuotaSettings($limitsPerMinute = 10000, $unit = 'minute', 1);

        try {
            for ($i = 1; $i <= $use; $i++) {
                QuotaManager::with($server, 'send')->count();
            }
        } finally {
            $this->assertEquals($i, $use + 1); // failed at the first try after limit is reached
        }
    }

    public function test_zero_credits()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 0);
        $server->setQuotaSettings($limitsPerMinute = 10000, $unit = 'minute', 1);
        $this->expectException(NoCreditsLeft::class);
        QuotaManager::with($server, 'send')->count();
    }

    public function test_exception_thrown_when_credits_file_is_missing_and_call_get_remaining_credits()
    {
        $server = $this->initServer();
        $server->setQuotaSettings($limitsPerMinute = 10000, $unit = 'minute', 1);
        $server->cleanupCreditsStorageFiles('send');
        $this->expectException(Exception::class);
        $server->getRemainingCredits('send');
    }

    public function test_exception_thrown_when_credits_file_is_missing_and_call_update_remaining_credits()
    {
        $server = $this->initServer();
        $server->setQuotaSettings($limitsPerMinute = 10000, $unit = 'minute', 1);
        $server->cleanupCreditsStorageFiles('send');
        $this->expectException(Exception::class);
        $server->updateRemainingCredits('send');
    }

    public function test_quota_is_not_counted_if_credit_check_fails()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 100);
        $server->setQuotaSettings($limitsPerMinute = 10000, $unit = 'minute', 1);

        QuotaManager::with($server, 'send')->count();

        // Ok, now 99 remaining
        $this->assertEquals($server->getRemainingCredits('send'), 99);
        $this->assertEquals($server->getCreditsUsed('send'), 1);

        // DELETE credit file
        // Then the correct way is to use QuotaManager::enforce
        // which does not count credits use and therefore does not throw exception (credits file missing)
        // However, if we use QuotaManager::count, credits check will fail (exception)
        // But we expect it that it does not log a credit use
        $server->cleanupCreditsStorageFiles('send');

        $this->expectException(Exception::class);
        QuotaManager::with($server, 'send')->count();

        // Credits used is still 1
        $this->assertEquals($server->getCreditsUsed('send'), 1);
    }

    public function test_zero_credits_but_without_credits_count()
    {
        $server = $this->initServer();
        $server->setCredits('send', QuotaManager::QUOTA_ZERO);
        $server->setQuotaSettings($limitsPerMinute = 10000, $unit = 'minute', 1);
        QuotaManager::with($server, 'send')->enforce();

        $this->assertEquals($server->getRemainingCredits('send'), QuotaManager::QUOTA_ZERO);
    }

    public function test_enforce_quota_limit_without_credits_count()
    {
        $server = $this->initServer();
        $credits = rand(10, 100);
        $server->setCredits('send', $credits);
        $server->setQuotaSettings($limitsPerMinute = 10000, $unit = 'minute', 1);
        QuotaManager::with($server, 'send')->enforce();

        $this->assertEquals($server->getRemainingCredits('send'), $credits);
    }

    public function test_enforce_quota_limit_without_credits_count_also_remove_credits_storage_file()
    {
        $server = $this->initServer();

        // Clean up the credits storage file
        $server->cleanupCreditsStorageFiles('send');

        $server->setQuotaSettings($limitsPerMinute = 10000, $unit = 'minute', 1);

        // enforce() ==> does not check credits
        QuotaManager::with($server, 'send')->enforce();

        // A dummy asset to avoid test warning (no assert in test)
        $this->assertTrue(true);

        // The following row will throw an exception
        // $server->getRemainingCredits('send');
    }

    public function test_zero_credits_per_minute()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 10000);
        $server->setQuotaSettings($limitsPerMinute = 0, $unit = 'minute', 1);
        $this->expectException(QuotaExceeded::class);
        QuotaManager::with($server, 'send')->count();
    }

    public function test_subscription_just_works()
    {
        $subscription = $this->initSubscription();
        $subscription->setCredits('send', 10000);
        $plan = Mockery::mock('plan');
        $plan->shouldReceive('getQuotaSettings')->andReturn([
            [
                'name' => "Plan's sending limit",
                'limit' => 10,
                'period_unit' => 'day',
                'period_value' => 1,
            ]

        ]);

        // Assign the mocked plan
        $subscription->plan = $plan;

        // Max allowed + 1
        $use  = 11;

        $this->expectException(QuotaExceeded::class);

        try {
            for ($i = 1; $i <= $use; $i++) {
                QuotaManager::with($subscription, 'send')->count();
            }
        } finally {
            $this->assertEquals($i, 11); // failed at the first try after limit is reached
        }
    }

    public function test_subscription_just_with_unlimited_sending_speed()
    {
        $subscription = $this->initSubscription();
        $subscription->setCredits('send', 100);
        $plan = Mockery::mock('plan');
        $plan->shouldReceive('getQuotaSettings')->andReturn([]);

        // Assign the mocked plan
        $subscription->plan = $plan;

        // Max allowed + 1
        $use  = 101;

        // Quota is null, so it just runs until sending credits == 0
        // Expect to see an exception at 101th use!
        $this->expectException(Exception::class);

        try {
            for ($i = 1; $i <= $use; $i++) {
                QuotaManager::with($subscription, 'send')->count();
            }
        } finally {
            $this->assertTrue($i == 101 && 0 === $subscription->getRemainingCredits('send')); // failed at the first try after limit is reached
        }
    }

    public function test_subscription_just_with_unlimited_sending_credits()
    {
        $subscription = $this->initSubscription();
        $subscription->setCredits('send', -1);
        $plan = Mockery::mock('plan');
        $plan->shouldReceive('getQuotaSettings')->andReturn([
            [
                'name' => "Plan's sending limit",
                'limit' => 999999,
                'period_unit' => 'month',
                'period_value' => 1,
            ]

        ]);

        // Assign the mocked plan
        $subscription->plan = $plan;
        $tests = 100;
        try {
            for ($i = 1; $i <= $tests; $i++) {
                QuotaManager::with($subscription, 'send')->count();
            }
        } finally {
            // failed at the first try after limit is reached
            $this->assertEquals(QuotaManager::QUOTA_UNLIMITED, $subscription->getRemainingCredits('send'));
            $this->assertEquals($subscription->getCreditsUsed('send'), $tests);
        }
    }

    public function test_subscription_does_not_allow_count_if_credits_setting_is_null()
    {
        $subscription = $this->initSubscription();

        // DELETE FILES AND DO NOT INITIATE CREDITS
        $subscription->cleanupCreditsStorageFiles('send');

        $plan = Mockery::mock('plan');
        $plan->shouldReceive('getQuotaSettings')->andReturn([]);
        $subscription->plan = $plan;

        $this->expectException(Exception::class);

        QuotaManager::with($subscription, 'send')->count();
    }

    public function test_subscription_does_not_allow_getting_remaining_credits_if_credits_setting_is_null()
    {
        $subscription = $this->initSubscription();

        // DELETE FILES AND DO NOT INITIATE CREDITS
        $subscription->cleanupCreditsStorageFiles('send');

        $plan = Mockery::mock('plan');
        $plan->shouldReceive('getQuotaSettings')->andReturn([]);

        // Assign the mocked plan
        $subscription->plan = $plan;
        $this->expectException(Exception::class);
        $subscription->getRemainingCredits('send');
    }

    public function test_email_verification_server_just_works_with_credits_limit()
    {
        $server = $this->initEmailVerificationServer();
        $server->setQuotaSettings('verify', 999, 'minute', 1);
        $server->setCredits('verify', 10);
        $tests = 11;
        $this->expectException(Exception::class);

        try {
            for ($i = 1; $i <= $tests; $i++) {
                QuotaManager::with($server, 'verify')->count();
            }
        } finally {
            // failed at the first try after limit is reached
            $this->assertEquals($server->getRemainingCredits('verify'), 0);
            $this->assertEquals($i, 11);
        }
    }

    private function prepareSendMessageTest()
    {
        // Server
        $server = $this->initServer();
        $server = Mockery::mock($server);
        $server->shouldReceive('send')->andReturn(null);

        // Subscription
        $subscription = $this->initSubscription();
        $plan = Mockery::mock('plan');


        // Assign the mocked plan
        $subscription->plan = $plan;

        // Customer
        $customer = Mockery::mock(new Customer());
        $customer->shouldReceive('activeSubscription')->andReturn($subscription);

        // Subscriber type is required as the new SendMessage(Subscriber $sub) has strict type parameter
        $subscriber = Mockery::mock(new Subscriber());
        $subscriber->shouldReceive('getEmail')->andReturn('test@example.com');

        // Logger
        $logger = Mockery::mock('logger');
        $logger->shouldReceive('info')->andReturn(null);
        $logger->shouldReceive('warning')->andReturn(null);
        $logger->shouldReceive('error')->andReturn(null);

        // Campaign
        $campaign = Mockery::mock(new Campaign());
        $campaign->shouldReceive('logger')->andReturn($logger);
        $campaign->shouldReceive('prepareEmail')->andReturn(null);
        $campaign->shouldReceive('trackMessage')->andReturn(null);
        $campaign->customer = $customer;

        return [$campaign, $subscriber, $server];
    }

    public function test_send_message_job_with_quota_exceeded()
    {
        list($campaign, $subscriber, $server) = $this->prepareSendMessageTest();

        // Server Quota
        $server->setQuotaSettings(0, $unit = 'minute', 1);

        // Subscription Quota
        $campaign->customer->activeSubscription()->plan->shouldReceive('getQuotaSettings')->andReturn([]);

        // SendMessage job
        $sendMsgJob = new SendMessage($campaign, $subscriber, $server);
        $sendMsgJob->send(function ($exception) use (&$outcome) {
            $outcome = $exception;
        });

        $this->assertInstanceOf(QuotaExceeded::class, $outcome);
    }

    public function test_send_message_job_with_quota_not_exceeded()
    {
        list($campaign, $subscriber, $server) = $this->prepareSendMessageTest();

        // Server Quota
        $server->setQuotaSettings(1, $unit = 'minute', 1);

        // Subscription Quota
        $campaign->customer->activeSubscription()->plan->shouldReceive('getQuotaSettings')->andReturn([]);

        // SendMessage job
        $sendMsgJob = new SendMessage($campaign, $subscriber, $server);
        $sendMsgJob->send(function ($exception) use (&$outcome) {
            $outcome = $exception;
        });

        $this->assertNull($outcome);
    }

    public function test_send_message_job_with_quota_exceeded_and_not_exceeded()
    {
        list($campaign, $subscriber, $server) = $this->prepareSendMessageTest();

        // Server Quota
        $server->setQuotaSettings(1, $unit = 'minute', 1);

        // Subscription Quota
        $campaign->customer->activeSubscription()->plan->shouldReceive('getQuotaSettings')->andReturn([]);

        // SendMessage job
        $sendMsgJob = new SendMessage($campaign, $subscriber, $server);
        $sendMsgJob->send(function ($exception) use (&$outcome) {
            $outcome = $exception;
        });

        // First send, okie
        $this->assertNull($outcome);

        $sendMsgJob->send(function ($exception) use (&$outcome) {
            $outcome = $exception;
        });

        // Second, quota exceeded
        $this->assertInstanceOf(QuotaExceeded::class, $outcome);
    }

    public function test_send_message_job_with_quota_exceeded_and_not_exceeded_subscription()
    {
        list($campaign, $subscriber, $server) = $this->prepareSendMessageTest();

        // Server Quota
        $server->setQuotaSettings(9999, $unit = 'minute', 1);

        // Subscription Quota
        $campaign->customer->activeSubscription()->plan->shouldReceive('getQuotaSettings')->andReturn([[
            'name' => "Plan's sending limit",
            'limit' => 1,
            'period_unit' => 'month',
            'period_value' => 1,
        ]]);

        // SendMessage job
        $sendMsgJob = new SendMessage($campaign, $subscriber, $server);
        $sendMsgJob->send(function ($exception) use (&$first) {
            $first = $exception;
        });

        // First send, okie
        $this->assertNull($first);

        $sendMsgJob->send(function ($exception) use (&$second) {
            $second = $exception;
        });

        // Second, quota exceeded
        $this->assertInstanceOf(QuotaExceeded::class, $second);
    }

    private function prepareVerifySubscriberTest()
    {
        // Server
        $server = $this->initEmailVerificationServer();

        // Subscription
        $subscription = $this->initSubscription();
        $plan = Mockery::mock('plan');

        // Assign the mocked plan
        $subscription->plan = $plan;

        // Customer
        $customer = Mockery::mock(new Customer());
        $customer->shouldReceive('activeSubscription')->andReturn($subscription);

        // Mail list
        $list = Mockery::mock(new MailList());
        $list->customer = $customer;

        // Subscriber type is required as the new SendMessage(Subscriber $sub) has strict type parameter
        $subscriber = Mockery::mock(new Subscriber());
        $subscriber->mailList = $list;
        $subscriber->shouldReceive('verify')->andReturn(null);

        return [$subscriber, $server];
    }

    public function test_email_verification_server_job_server_quota_exceeded()
    {
        // Test setup
        list($subscriber, $server) = $this->prepareVerifySubscriberTest();
        $server->setQuotaSettings('verify', 2, $unit = 'minute', 1);
        $subscription = $subscriber->mailList->customer->activeSubscription();
        $subscription->plan->shouldReceive('getQuotaSettings')->andReturn([]);

        // SendMessage job
        $verifyJob = new VerifySubscriber($subscriber, $server);

        // First shot
        $verifyJob->doVerify(function ($exception) use (&$outcome) {
            $outcome = $exception;
        });

        // Second shot
        $verifyJob->doVerify(function ($exception) use (&$outcome) {
            $outcome = $exception;
        });

        // Just fine
        $this->assertNull($outcome);

        // Second short
        $verifyJob->doVerify(function ($exception) use (&$outcome) {
            $outcome = $exception;
        });

        // Second, quota exceeded
        $this->assertInstanceOf(QuotaExceeded::class, $outcome);
        $this->assertEquals($server->getCreditsUsed('verify'), 2);

        // IMPORTANT: sacrisfy one credit here, since 'subscription.verify' is validate first (and pass) ==> so used credits is 3
        $this->assertEquals($subscription->getCreditsUsed('verify'), 3);
    }

    public function test_email_verification_server_job_plan_quota_exceeded()
    {
        // Test setup
        list($subscriber, $server) = $this->prepareVerifySubscriberTest();
        $server->setQuotaSettings('verify', 9999, $unit = 'minute', 1);
        $subscription = $subscriber->mailList->customer->activeSubscription();
        $subscription->plan->shouldReceive('getQuotaSettings')->andReturn([[
            'name' => "Plan's sending limit",
            'limit' => 2,
            'period_unit' => 'hour',
            'period_value' => 1,
        ]]);

        // SendMessage job
        $verifyJob = new VerifySubscriber($subscriber, $server);

        // First shot
        $verifyJob->doVerify(function ($exception) use (&$outcome) {
            $outcome = $exception;
        });

        // Second shot
        $verifyJob->doVerify(function ($exception) use (&$outcome) {
            $outcome = $exception;
        });

        // Just fine
        $this->assertNull($outcome);

        // Second short
        $verifyJob->doVerify(function ($exception) use (&$outcome) {
            $outcome = $exception;
        });

        // Second, quota exceeded
        $this->assertInstanceOf(QuotaExceeded::class, $outcome);
        $this->assertEquals($server->getCreditsUsed('verify'), 2);
        $this->assertEquals($subscription->getCreditsUsed('verify'), 2);
    }
}
