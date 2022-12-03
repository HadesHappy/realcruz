<?php

/**
 * SubAccountSendGrid class.
 *
 * Model class for SubUserSendGrid
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   MVC Model
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 */

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log as LaravelLog;

class SubAccountSendGrid extends SubAccount
{
    protected $table = 'sub_accounts';

    /**
     * Association, retrieve the corresponding SendGrid sending server.
     */
    public function sendingServerSendGrid()
    {
        return $this->belongsTo('Acelle\Model\SendingServerSendGrid', 'sending_server_id');
    }

    /**
     * Generate a new API key and update it for the sub-account.
     */
    public function updateApiKey()
    {
        try {
            // create an API key
            $response = $this->getApiClient()->createApiKey(['name' => 'Acelle Mail']);
            $this->api_key_id = $response['api_key_id'];
            $this->api_key = $response['api_key'];
            $this->save();
            LaravelLog::info("API key updated for SendGrid subuser {$this->getSubAccountUsername()}");
        } catch (\Exception $ex) {
            throw new \Exception('Cannot update API key. Error message from SendGrid: '.$ex->getMessage());
        }
    }

    /**
     * Overwrite the delete() method to also clear the pending jobs.
     */
    public function delete()
    {
        $this->getMasterApiClient()->deleteSubUser($this->getSubAccountUsername());
        LaravelLog::info('Sub account deleted: '.$this->getSubAccountUsername());
        parent::delete();
    }

    /**
     * Get the sub-account API client (authenticated).
     */
    public function getApiClient()
    {
        $client = new \Acelle\Extra\SendGrid(['username' => $this->getSubAccountUsername(), 'password' => decrypt($this->password)]);

        return $client;
    }

    /**
     * Get the master API client (authenticated).
     */
    public function getMasterApiClient()
    {
        $master = $this->sendingServerSendGrid;
        $client = new \Acelle\Extra\SendGrid(['api' => $master->api_key]);

        return $client;
    }

    /**
     * Overwrite the delete() method to also clear the pending jobs.
     */
    public function createSubAccount()
    {
        try {
            $client = $this->getMasterApiClient();

            if ($client->subUserExists($this->getSubAccountUsername())) {
                LaravelLog::info("SendGrid subuser {$this->getSubAccountUsername()} already exists");

                return;
            }

            LaravelLog::info("Trying to create SendGrid subuser {$this->getSubAccountUsername()}");
            $response = $client->createSubUser([
                'username' => $this->getSubAccountUsername(),
                'email' => $this->email,
                'password' => decrypt($this->password),
            ]);
            LaravelLog::info("SendGrid subuser {$this->getSubAccountUsername()} created");
        } catch (\Exception $ex) {
            throw new \Exception('Cannot create SendGrid subuser. Error message from SendGrid: '.$ex->getMessage());
        }
    }

    /**
     * Setup a sub-account for the subscription
     * A unique sub-account is created for every [customer, sending server]
     * Create one if not exist yet.
     */
    public static function setup($params)
    {
        $instance = self::findByKeySet($params['customer_id'], $params['sending_server_id']);

        if (is_null($instance)) {
            $instance = new self();
            $instance->email = $params['email'];
            $instance->customer_id = $params['customer_id'];
            $instance->sending_server_id = $params['sending_server_id'];
            $instance->password = encrypt(uniqid());
            $instance->username = $instance->getSubAccountUsername();
            $instance->save();
        }

        $instance->createSubAccount(); // if one does not exist yet, otherwise, just return ok
        $instance->updateApiKey(); // just create a new API KEY for new subscription, @todo: is this a problem?

        return $instance;
    }

    /**
     * Genereate a globally unique SendGrid subuser username for [customer, sending server]
     * As a result, many app can share the same SendGrid master account without worrying about name conflict.
     */
    public function getSubAccountUsername()
    {
        // return a unique username for subaccount
        return 'acellemail_'.substr(md5("{$this->sendingServer->uid}{$this->customer->uid}"), 0, 8);
    }

    /**
     * Find the corresponding sub-account for [customer, sending server].
     */
    public static function findByKeySet($customer_id, $sending_server_id)
    {
        return self::where('customer_id', $customer_id)->where('sending_server_id', $sending_server_id)->first();
    }
}
