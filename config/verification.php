<?php

return [
    'services' => [
        [
            'id' => 'emailable.com',
            'name' => 'Emailable (recommended)',
            'uri' => 'https://api.emailable.com/v1/verify?email={EMAIL}&api_key={API_KEY}',
            'request_type' => 'GET',
            'fields' => [ 'api_key' ],
            'result_xpath' => '$.state',
            'result_map' => [ 'deliverable' => 'deliverable', 'undeliverable' => 'undeliverable', 'risky' => 'risky', 'unknown' => 'unknown' ]
        ],[
            'id' => 'kickbox.io',
            'name' => 'Kickbox IO',
            'uri' => 'https://api.kickbox.io/v2/verify?email={EMAIL}&apikey={API_KEY}',
            'request_type' => 'GET',
            'fields' => [ 'api_key' ],
            'result_xpath' => '$.result',
            'result_map' => [ 'deliverable' => 'deliverable', 'undeliverable' => 'undeliverable', 'risky' => 'risky', 'unknown' => 'unknown' ]
        ], [
            'id' => 'thechecker.co',
            'name' => 'TheChecker CO',
            'uri' => 'https://api.thechecker.co/v1/verify?email={EMAIL}&api_key={API_KEY}',
            'request_type' => 'GET',
            'fields' => [ 'api_key' ],
            'result_xpath' => '$.result',
            'result_map' => [ 'deliverable' => 'deliverable', 'undeliverable' => 'undeliverable', 'risky' => 'risky', 'unknown' => 'unknown' ]
        ],
        [
            'id' => 'zerobounce.net',
            'name' => 'Zero Bounce',
            'uri' => 'https://api.zerobounce.net/v1/validate?apikey={API_KEY}&email={EMAIL}',
            'request_type' => 'GET',
            'fields' => [ 'api_key' ],
            'result_xpath' => '$.status',
            'result_map' => [ 'Valid' => 'deliverable', 'Invalid' => 'undeliverable', 'Unknown' => 'unknown', 'Abuse' => 'undeliverable', 'spamtrap' => 'undeliverable', 'Spamtrap' => 'undeliverable', 'catch-all' => 'deliverable', 'Catch-all' => 'deliverable', 'Catch-All' => 'deliverable', 'do_not_mail' => 'undeliverable', 'Do_not_mail' => 'undeliverable', 'Do_Not_Mail' => 'undeliverable']
        ],
        [
            'id' => 'verify-email.org',
            'name' => 'VerifyEmail ORG',
            'uri' => 'https://app.verify-email.org/api/v1/{API_KEY}/verify/{EMAIL}',
            'request_type' => 'GET',
            'fields' => [ 'api_key' ],
            'result_xpath' => '$.status',
            'result_map' => [ '1' => 'deliverable', '0' => 'undeliverable', '-1' => 'unknown' ]
        /*
        ], [
            'id' => 'proofy.io',
            'name' => 'proofy.io',
            'uri' => 'https://api.proofy.io/verifyaddr?aid={USERNAME}&key={API_KEY}&email={EMAIL}',
            'request_type' => 'GET',
            'fields' => [ 'username', 'api_key' ],
            'result_xpath' => '$.mail.statusName',
            'result_map' => [ 'deliverable' => 'deliverable', 'undeliverable' => 'undeliverable', 'risky' => 'risky' ]
        */
        ], [
            'id' => 'everifier.org',
            'name' => 'Everifier ORG',
            'uri' => 'https://api.everifier.org/v1/{API_KEY}/verify/{EMAIL}',
            'request_type' => 'GET',
            'fields' => [ 'api_key' ],
            'result_xpath' => '$.*.status',
            'result_map' => [ '1' => 'deliverable', '0' => 'undeliverable', '-1' => 'risky' ]
        ], [
            'id' => 'verifyre.co',
            'name' => 'Verifyre CO',
            'uri' => 'https://www.verifyre.co/app/check?id={USERNAME}&key={API_KEY}&mail={EMAIL}',
            'request_type' => 'GET',
            'fields' => [ 'username', 'api_key' ],
            'result_xpath' => '$.mail.status',
            'result_map' => [ '1' => 'deliverable', '2' => 'risky', '3' => 'undeliverable' ]
        ], [
            'id' => 'localmail.io',
            'name' => 'Localmail IO',
            'uri' => 'https://api.localmail.io/v1/mail/verify?key={API_KEY}&email={EMAIL}',
            'request_type' => 'GET',
            'fields' => [ 'api_key' ],
            'result_xpath' => '$.result',
            'result_map' => [ 'deliverable' => 'deliverable', 'unknown' => 'unknown', 'risky' => 'risky', 'undeliverable' => 'undeliverable' ]
        ], [
            'id' => 'debounce.io',
            'name' => 'Debounce IO',
            'uri' => 'https://api.debounce.io/v1/?api={API_KEY}&email={EMAIL}',
            'request_type' => 'GET',
            'fields' => [ 'api_key' ],
            'result_xpath' => '$.debounce.result',
            'result_map' => [ 'Safe to Send' => 'deliverable', 'Unknown' => 'unknown', 'Risky' => 'risky', 'Invalid' => 'undeliverable' ]
        ], [
            'id' => 'emailchecker.com',
            'name' => 'EmailChecker',
            'uri' => 'https://api.emailverifyapi.com/v3/lookups/json?email={EMAIL}&key={API_KEY}',
            'request_type' => 'GET',
            'fields' => [ 'api_key' ],
            'result_xpath' => '$.deliverable',
            'result_map' => [ 'true' => 'deliverable', 'false' => 'undeliverable' ]
        ],[
            'id' => 'cloudvision.io',
            'name' => 'Cloud Vision',
            'uri' => 'https://dev-marketing.cloudvision.io/api/v1/verify?email={EMAIL}&api_token={API_KEY}',
            'request_type' => 'GET',
            'fields' => [ 'api_key' ],
            'result_xpath' => '$.result',
            'result_map' => [ 'deliverable' => 'deliverable', 'undeliverable' => 'undeliverable' ]
        ],[
            'id' => 'cloudmersive.com',
            'name' => 'Cloudmersive',
            'uri' => 'https://api.cloudmersive.com/validate/email/address/full',
            'request_type' => 'POST',
            'post_data' => '{EMAIL}',
            'post_headers' => [ 'Content-Type' => 'application/json', "Apikey" => "{API_KEY}" ],
            'fields' => [ 'api_key' ],
            'result_xpath' => '$.ValidAddress',
            'result_map' => [ 'true' => 'deliverable', 'false' => 'undeliverable' ]
        ],[
            'id' => 'emaillistvalidation.com',
            'name' => 'Emaillist Validation',
            'uri' => 'https://app.emaillistvalidation.com/api/verifEmail?secret={API_KEY}&email={EMAIL}',
            'request_type' => 'GET',
            'response_type' => 'plain',
            'fields' => [ 'api_key' ],
            'result_map' => [
                'ok' => 'deliverable',
                'ok_for_all' => 'deliverable',
                'ok_for_all | ok_for_all' => 'deliverable',
                'ok_for_all|ok_for_all' => 'deliverable',
                'email_disabled' => 'undeliverable',
                'risky' => 'risky',
                'unknown' => 'unknown'
            ]
        ],[
            'id' => 'bounceless.io',
            'name' => 'Bounceless.io',
            'uri' => 'https://apps.bounceless.io/api/verifyEmail?secret={API_KEY}&email={EMAIL}',
            'request_type' => 'GET',
            'fields' => [ 'api_key' ],
            'response_type' => 'plain',
            'result_map' => [
                'ok' => 'deliverable',
                'unknown' => 'unknown',
                'incorrect' => 'undeliverable',
                'fail' => 'undeliverable',
                'email_disabled' => 'undeliverable',
            ]
        ],
        [
            'id' => 'werify.email',
            'name' => 'werify.email',
            'uri' => 'https://api.millionverifier.com/api/v3/?api={API_KEY}&email={EMAIL}',
            'request_type' => 'GET',
            'fields' => [ 'api_key' ],
            'result_xpath' => '$.result',
            'result_map' => [
                'ok' => 'deliverable',
                'catch_all' => 'risky',
                'unknown' => 'unknown',
                'disposable' => 'undeliverable',
                'invalid' => 'undeliverable',
                'error' => 'undeliverable',
            ]
        ]
    ]
];
