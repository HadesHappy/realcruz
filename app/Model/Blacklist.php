<?php

/**
 * Blacklist class.
 *
 * Model for blacklisted email addresses
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
use Acelle\Library\Tool;
use File;

class Blacklist extends Model
{
    public const IMPORT_TEMP_DIR = 'app/tmp';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'reason',
    ];

    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return self::select('blacklists.*');
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('customer_id');
    }

    /**
     * Get all items.
     *
     * @return collect
     */
    public function delist($customer = null)
    {
        if (is_null($customer)) {
            $sql = sprintf('UPDATE %s SET status = %s WHERE status = %s AND email = %s', table('subscribers'), db_quote(Subscriber::STATUS_SUBSCRIBED), db_quote(Subscriber::STATUS_BLACKLISTED), db_quote($this->email));
        } else {
            // slow: $sql = sprintf('UPDATE %s SET status = %s WHERE status = %s AND email = %s AND mail_list_id IN (SELECT id FROM %s WHERE customer_id = %s)', table('subscribers'), db_quote(Subscriber::STATUS_SUBSCRIBED), db_quote(Subscriber::STATUS_BLACKLISTED), db_quote($this->email), table('mail_lists'), $customer->id);
            $sql = sprintf('UPDATE %s s INNER JOIN %s m ON m.id = s.mail_list_id SET s.status = %s WHERE s.status = %s AND s.email = %s AND m.customer_id = %s', table('subscribers'), table('mail_lists'), db_quote(Subscriber::STATUS_SUBSCRIBED), db_quote(Subscriber::STATUS_BLACKLISTED), db_quote($this->email), $customer->id);
        }
        \DB::statement($sql);
    }

    /**
     * Blacklist all subscribers of the same email address.
     *
     * @return collect
     */
    public static function doBlacklist($customer = null)
    {
        $sql = sprintf('UPDATE %s s INNER JOIN %s b ON b.email = s.email SET status = %s WHERE s.status = %s AND b.customer_id IS NULL', table('subscribers'), table('blacklists'), db_quote(Subscriber::STATUS_BLACKLISTED), db_quote(Subscriber::STATUS_SUBSCRIBED));
        \DB::statement($sql);

        // user wide blacklist
        if (!is_null($customer)) {
            $sql = sprintf('UPDATE %s s INNER JOIN %s b ON b.email = s.email INNER JOIN %s m ON m.id = s.mail_list_id SET s.status = %s WHERE s.status = %s AND m.customer_id = %s', table('subscribers'), table('blacklists'), table('mail_lists'), db_quote(Subscriber::STATUS_BLACKLISTED), db_quote(Subscriber::STATUS_SUBSCRIBED), $customer->id);
            \DB::statement($sql);
        }
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = self::select('blacklists.*');

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('blacklists.email', 'like', '%'.$keyword.'%');
                });
            }
        }

        // Other filter
        if (!empty($request->customer_id)) {
            $query = $query->where('blacklists.customer_id', '=', $request->customer_id);
        }

        if (!empty($request->admin_id)) {
            $query = $query->whereNull('customer_id');
        }

        return $query;
    }

    /**
     * Search items.
     *
     * @return collect
     */
    public static function search($request)
    {
        $query = self::filter($request);

        if (!empty($request->sort_order)) {
            $query = $query->orderBy($request->sort_order, $request->sort_direction);
        }

        return $query;
    }

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * Import from file.
     *
     * @return collect
     */
    public static function import($file, $customer = null, $progressCallback = null)
    {
        if (!is_null($progressCallback)) {
            $progressCallback($processed = 0, $total = 0, $failed = 0, $message = trans('messages.blacklist.import_process_new'));
        }

        $content = \File::get($file);
        $lines = preg_split('/\r\n|\r|\n/', $content);
        $total = count($lines);
        $failed = 0;
        $processed = 0;

        if (!is_null($progressCallback)) {
            $progressCallback($processed, $total, $failed, $message = 'Loading file content...');
        }

        foreach ($lines as $number => $line) {
            $email = trim(strtolower($line));

            // Add to blacklist
            if (Tool::isValidEmail($email)) {
                // Add to blacklist
                if (!is_null($customer)) {
                    $customer->addEmaillToBlacklist($email);
                } else {
                    self::addEmaill($email);
                }
            } else {
                $failed += 1;
            }

            $processed += 1;

            if (!is_null($progressCallback)) {
                $progressCallback($processed, $total, $failed, trans('messages.blacklist.import_process_running', ['processed' => $processed]));
            }
        }

        self::doBlacklist($customer);

        if (!is_null($progressCallback)) {
            $progressCallback($processed = $total, $total, $failed, trans('messages.blacklist.import_process_complete', ['processed' => $total]));
        }
    }

    public static function upload(\Illuminate\Http\UploadedFile $httpFile)
    {
        $filename = "blacklst-import-".uniqid().".txt";
        $path = storage_path(self::IMPORT_TEMP_DIR);

        // store it to storage/
        $httpFile->move($path, $filename);

        // Example of outcome: /home/acelle/storage/app/tmp/import-000000.csv
        $filepath = join_paths($path, $filename);

        return $filepath;
    }

    /**
     * Add email to admin blacklist.
     */
    public static function addEmaill($email)
    {
        $email = trim(strtolower($email));

        if (Tool::isValidEmail($email)) {
            $exist = self::global()->where('email', '=', $email)->count();

            if (!$exist) {
                $blacklist = new self();
                $blacklist->email = $email;
                $blacklist->save();
            }
        }
    }
}
