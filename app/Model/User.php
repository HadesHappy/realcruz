<?php

/**
 * User class.
 *
 * Model class for user
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

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Acelle\Notifications\ResetPassword;
use Acelle\Library\Log as MailLog;
use Acelle\Library\ExtendedSwiftMessage;
use Acelle\Model\Setting;
use Illuminate\Http\UploadedFile;
use App;
use URL;
use File;
use Acelle\Library\Tool;
use Acelle\Library\Traits\HasUid;
use Acelle\Library\Facades\Hook;

class User extends Authenticatable
{
    use Notifiable;
    use HasUid;

    /*******
     * Important: Storage location: User vs Customer
     * One customer (account) may have one or more users
     *
     * User has its own location for storing:
     * + Lock files
     * + Assets (which is used by File Manager)
     *
     * Customer has a common place to store account's assets (available to all users of the same account)
     * + Templates
     * + Attachments (account-wide, not individual user)
     *
     */
    public const BASE_DIR = 'app/users';
    public const ASSETS_DIR = 'home/files';
    public const ASSETS_THUMB_DIR = 'home/thumbs';
    public const PROFILE_IMAGE_PATH = 'home/avatar';
    public const PROFILE_THUMB_PATH = 'home/avatar-thumb';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'first_name', 'last_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    public function admin()
    {
        return $this->hasOne('Acelle\Model\Admin');
    }

    public function createUserDirectories()
    {
        $paths = [
            $this->getBasePath(),
            $this->getAssetsPath(),
            $this->getThumbsPath(),
        ];

        foreach ($paths as $path) {
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
        }
    }

    /**
     * Get authenticate from file.
     *
     * @return string
     */
    public static function getAuthenticateFromFile()
    {
        $path = base_path('.authenticate');

        if (file_exists($path)) {
            $content = \File::get($path);
            $lines = explode("\n", $content);
            if (count($lines) > 1) {
                $demo = session()->get('demo');
                if (!isset($demo) || $demo == 'backend') {
                    return ['email' => $lines[0], 'password' => $lines[1]];
                } else {
                    return ['email' => $lines[2], 'password' => $lines[3]];
                }
            }
        }

        return ['email' => '', 'password' => ''];
    }

    /**
     * Send regitration activation email.
     *
     * @return string
     */
    public function sendActivationMail($name = null)
    {
        $layout = \Acelle\Model\Layout::where('alias', 'registration_confirmation_email')->first();
        $token = $this->getToken();

        $layout->content = str_replace('{ACTIVATION_URL}', join_url(config('app.url'), action('UserController@activate', ['token' => $token], false)), $layout->content);
        $layout->content = str_replace('{CUSTOMER_NAME}', $name, $layout->content);

        $name = is_null($name) ? trans('messages.to_email_name') : $name;

        // build the message
        $message = new ExtendedSwiftMessage();
        $message->setEncoder(new \Swift_Mime_ContentEncoder_PlainContentEncoder('8bit'));
        $message->setContentType('text/html; charset=utf-8');

        $message->setSubject($layout->subject);
        $message->setTo([$this->email => $name]);
        $message->setReplyTo(Setting::get('mail.reply_to'));
        $message->addPart($layout->content, 'text/html');

        $mailer = App::make('xmailer');
        $result = $mailer->sendWithDefaultFromAddress($message);

        if (array_key_exists('error', $result)) {
            throw new \Exception($result['error']);
        }

        MailLog::info('Sent activation email to '.$this->email);
    }

    /**
     * The rules for validation.
     *
     * @var array
     */
    public function rules()
    {
        $rules = array(
            'email' => 'required|email|unique:users,email,'.$this->id.',id',
            'first_name' => 'required',
            'last_name' => 'required',
            'timezone' => 'required',
            'language_id' => 'required',
        );

        if (isset($this->id)) {
            $rules['password'] = 'nullable|confirmed|min:5|max:255';
        } else {
            $rules['password'] = 'required|confirmed|min:5|max:255';
        }

        return $rules;
    }

    /**
     * The rules for validation.
     *
     * @var array
     */
    public function registerRules()
    {
        $rules = array(
            'email' => 'required|email|unique:users,email,'.$this->id.',id',
            'first_name' => 'required',
            'last_name' => 'required',
            'timezone' => 'required',
            'language_id' => 'required',
        );

        if (isset($this->id)) {
            $rules['password'] = 'min:5|max:255';
        } else {
            $rules['password'] = 'required|max:255';
        }

        return $rules;
    }

    /**
     * The rules for validation via api.
     *
     * @var array
     */
    public function apiRules()
    {
        return array(
            'email' => 'required|email|unique:users,email,'.$this->id.',id',
            'first_name' => 'required',
            'last_name' => 'required',
            'timezone' => 'required',
            'language_id' => 'required',
            'password' => 'required|min:5',
        );
    }

    /**
     * The rules for validation via api.
     *
     * @var array
     */
    public function apiUpdateRules($request)
    {
        $arr = [];

        if (isset($request->email)) {
            $arr['email'] = 'required|email|unique:users,email,'.$this->id.',id';
        }
        if (isset($request->first_name)) {
            $arr['first_name'] = 'required';
        }
        if (isset($request->last_name)) {
            $arr['last_name'] = 'required';
        }
        if (isset($request->timezone)) {
            $arr['timezone'] = 'required';
        }
        if (isset($request->language_id)) {
            $arr['language_id'] = 'required';
        }
        if (isset($request->password)) {
            $arr['password'] = 'min:5|max:255';
        }

        return $arr;
    }

    /**
     * Display customer name: first_name last_name.
     *
     * @var string
     */
    public function displayName()
    {
        return htmlspecialchars(trim($this->first_name.' '.$this->last_name));
    }

    /**
     * User activation.
     *
     * @return string
     */
    public function userActivation()
    {
        return $this->hasOne('Acelle\Model\userActivation');
    }

    /**
     * Create activation token for user.
     *
     * @return string
     */
    public function getToken()
    {
        $token = \Acelle\Model\UserActivation::getToken();

        $userActivation = $this->userActivation;

        if (!is_object($userActivation)) {
            $userActivation = new \Acelle\Model\UserActivation();
            $userActivation->user_id = $this->id;
        }

        $userActivation->token = $token;
        $userActivation->save();

        return $token;
    }

    /**
     * Set user is activated.
     *
     * @return bool
     */
    public function setActivated()
    {
        $this->activated = true;
        $this->save();
    }

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating list.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            $item->uid = $uid;

            // Add api token
            $item->api_token = str_random(60);
        });
    }

    /**
     * Check if user has admin account.
     */
    public function isAdmin()
    {
        return is_object($this->admin);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token)
    {
        // $this->notify(new ResetPassword($token, url('password/reset', $token)));

        $resetPasswordUrl = url('password/reset', $token);
        $htmlContent = '<p>Please click the link below to reset your password:<br><a href="'.$resetPasswordUrl.'">'.$resetPasswordUrl.'</a>';

        // build the message
        $message = new ExtendedSwiftMessage();
        $message->setEncoder(new \Swift_Mime_ContentEncoder_PlainContentEncoder('8bit'));
        $message->setContentType('text/html; charset=utf-8');

        $message->setSubject('Password Reset');
        $message->setTo($this->email);
        $message->setReplyTo(Setting::get('mail.reply_to'));
        $message->addPart($htmlContent, 'text/html');

        $mailer = App::make('xmailer');
        $result = $mailer->sendWithDefaultFromAddress($message);

        if (array_key_exists('error', $result)) {
            throw new \Exception($result['error']);
        }
    }

    public function getLockPath($path)
    {
        $base = $this->getBasePath('locks');

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    public function getAssetsPath($path = null)
    {
        $base = $this->getBasePath(self::ASSETS_DIR);

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    public function getThumbsPath($path = null)
    {
        $base = $this->getBasePath(self::ASSETS_THUMB_DIR);

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    public function getBasePath($path = null)
    {
        $base = storage_path(join_paths(self::BASE_DIR, $this->uid));

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    /**
     * Generate one time token.
     */
    public function generateOneTimeToken()
    {
        $this->one_time_api_token = generateRandomString(32);
        $this->save();
    }

    /**
     * Clear one time token.
     */
    public function clearOneTimeToken()
    {
        $this->one_time_api_token = null;
        $this->save();
    }

    public function getAssetsSubUrl()
    {
        $appSubDirectory = \Acelle\Helpers\getAppSubdirectory();
        $appSubDirectory = (is_null($appSubDirectory)) ? '' : $appSubDirectory;

        // Returns a relative subpath like "/files/000000"
        $subpath = route('user_files', ['uid' => $this->uid], false);
        return join_paths($appSubDirectory, $subpath);
    }

    public function getThumbsSubUrl()
    {
        $appSubDirectory = \Acelle\Helpers\getAppSubdirectory();
        $appSubDirectory = (is_null($appSubDirectory)) ? '' : $appSubDirectory;

        // Returns a relative subpath like "/thumbs/000000"
        $subpath = route('user_thumbs', ['uid' => $this->uid], false) ;
        return join_paths($appSubDirectory, $subpath);
    }

    public function getFilemanagerConfig()
    {
        // Create user directories if not exists
        $this->createUserDirectories();

        // Base URL with port, but without subpath
        $baseUrl = parse_url(url('/'));

        // port may be null
        if (!array_key_exists('port', $baseUrl)) {
            $baseUrl['port'] = '';
        }

        $baseUrl = "{$baseUrl['scheme']}://{$baseUrl['host']}{$baseUrl['port']}";

        // Limitation
        // Since this method is called from within the dialog.php file, there is no way to know if whether or not it is an admin or customer session
        // So, there is no chance to apply quota here
        if ($this->customer) {
            // Limited by plan
            $maxSizeTotal = $this->customer->getOption("max_size_upload_total") > 0 ? $this->customer->getOption("max_size_upload_total") : 2048;
            $maxSizeUpload = $this->customer->getOption("max_file_size_upload") > 0 ? $this->customer->getOption("max_file_size_upload") : 2048;
        } else {
            // Virtually unlimited
            $maxSizeTotal = 2048;
            $maxSizeUpload = 2048;
        }

        $config = [
            'base_url' => $baseUrl,

            // The `upload_dir` is needed to make the final file URL which is compatible with `user_files` route
            // For example: "/files/000000/example.jpg"
            'upload_dir' => join_paths('/', $this->getAssetsSubUrl(), '/'),
            'thumb_dir' => join_paths('/', $this->getThumbsSubUrl(), '/'),
            'thumbs_upload_dir' => join_paths('/', $this->getThumbsSubUrl(), '/'),
            // relative path from filemanager folder to upload folder, WITH FINAL /
            'current_path' => join_paths('../../storage/', self::BASE_DIR, $this->uid, self::ASSETS_DIR, '/'),
            // relative path from filemanager folder to upload folder, WITH FINAL /
            'thumbs_base_path' => join_paths('../../storage/', self::BASE_DIR, $this->uid, self::ASSETS_THUMB_DIR, '/'),
            'MaxSizeTotal' => $maxSizeTotal,
            'MaxSizeUpload' => $maxSizeUpload,
        ];

        return $config;
    }

    public function getProfileImageUrl()
    {
        $path = $this->getProfileImagePath();
        if (file_exists($path)) {
            return \Acelle\Helpers\generatePublicPath($path) . '?v=' . md5_file($this->getProfileImagePath());
        } else {
            return URL::asset('images/user-placeholder.svg');
        }
    }

    public function getProfileThumbUrl()
    {
        $path = $this->getProfileThumbPath();
        if (file_exists($path)) {
            return \Acelle\Helpers\generatePublicPath($path);
        } else {
            return URL::asset('images/user-placeholder.svg');
        }
    }

    public function getProfileImagePath()
    {
        return $this->getBasePath(self::PROFILE_IMAGE_PATH);
    }

    public function getProfileThumbPath()
    {
        return $this->getBasePath(self::PROFILE_THUMB_PATH);
    }

    /**
     * Upload and resize avatar.
     *
     * @var void
     */
    public function uploadProfileImage(UploadedFile $file)
    {
        // Full path: /storage/app/users/000000/home/avatar
        $path = $this->getProfileImagePath();

        // File name: avatar
        $filename = basename($path);

        // The base dir: /storage/app/users/000000/home/
        $dirname = dirname($path);

        // save to server at /storage/app/users/000000/home/avatar
        $file->move($dirname, $filename);

        // create thumbnails and replace the original image with the the small-sized thumbnail
        $img = \Image::make($path);
        $img->fit(120, 120)->save($path);

        return $path;
    }

    public function removeProfileImage()
    {
        $path = $this->getProfileImagePath();
        if (file_exists($path)) {
            File::delete($path);
        }

        $thumb = $this->getProfileThumbPath();
        if (file_exists($thumb)) {
            File::delete($thumb);
        }
    }

    public function deleteAndCleanup()
    {
        // User's storage location
        // For example: storage/app/users/000000/
        $path = $this->getBasePath();
        if (file_exists($path)) {
            Tool::xdelete($path);
        }

        $this->delete();
    }

    public static function createCustomer($params)
    {
        // Create new user/customer
        $user = new self();

        if (isset($params['name'])) {
            $user->first_name = $params['name'];
        }

        if (isset($params['email'])) {
            $user->email = $params['email'];
        }

        if (isset($params['google_id'])) {
            $user->google_id = $params['google_id'];
        }

        if (isset($params['google_token'])) {
            $user->google_token = $params['google_token'];
        }

        if (isset($params['google_refresh_token'])) {
            $user->google_refresh_token = $params['google_refresh_token'];
        }

        if (isset($params['facebook_id'])) {
            $user->facebook_id = $params['facebook_id'];
        }

        if (isset($params['facebook_token'])) {
            $user->facebook_token = $params['facebook_token'];
        }

        if (isset($params['facebook_refresh_token'])) {
            $user->facebook_refresh_token = $params['facebook_refresh_token'];
        }

        $user->password = bcrypt(str_random(8));
        $user->activated = true;
        $user->status = 'active';
        $user->save();

        // Save current user info
        $customer = \Acelle\Model\Customer::newCustomer();
        $customer->language_id = language()->id;
        $customer->status = 'active';
        $customer->timezone = date_default_timezone_get();
        $customer->save();

        // assign customer
        $user->customer_id = $customer->id;
        $user->save();

        //
        Hook::execute('customer_added', [$customer]);
    }
}
