<?php

/**
 * Tool class.
 *
 * Misc helper tool
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   Acelle Library
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

namespace Acelle\Library;

use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\File;
use Exception;

class Tool
{
    /**
     * Copy a file, or recursively copy a folder and its contents.
     *
     * @param string $source      Source path
     * @param string $dest        Destination path
     * @param int    $permissions New folder creation permissions
     *
     * @return bool Returns true on success, false on failure
     */
    public static function xcopy($source, $dest, $permissions = 0755)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            $oldmask = umask(0);
            mkdir($dest, $permissions, true);
            umask($oldmask);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            self::xcopy("$source/$entry", "$dest/$entry", $permissions);
        }

        // Clean up
        $dir->close();

        return true;
    }

    /**
     * Delete a file, or recursively delete a folder and its contents.
     *
     * @param string $source Source path
     *
     * @return bool Returns true on success, false on failure
     */
    public static function xdelete($file)
    {
        if (!file_exists($file)) {
            throw new Exception("File {$file} does not exist");
        }

        if (is_link($file) || is_file($file)) {
            File::delete($file);
        } else {
            File::deleteDirectory($file);
        }

        return true;
    }

    /**
     * Get all time zone.
     *
     * @var array
     */
    public static function allTimeZones()
    {
        // Get all time zones with offset
        $zones_array = array();
        $timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $zones_array[$key]['zone'] = $zone;
            $zones_array[$key]['text'] = '(GMT'.date('P', $timestamp).') '.$zones_array[$key]['zone'];
            $zones_array[$key]['order'] = str_replace('-', '1', str_replace('+', '2', date('P', $timestamp))).$zone;
        }

        // sort by offset
        usort($zones_array, function ($a, $b) {
            return strcmp($a['order'], $b['order']);
        });

        return $zones_array;
    }

    /**
     * Get options array for select box.
     *
     * @var array
     */
    public static function getTimezoneSelectOptions()
    {
        $arr = [];
        foreach (self::allTimeZones() as $timezone) {
            $row = ['value' => $timezone['zone'], 'text' => $timezone['text']];
            $arr[] = $row;
        }

        return $arr;
    }

    /**
     * Change singular to plural.
     *
     * @param       string
     *
     * @return string
     */
    public static function getPluralPrase($phrase, $value)
    {
        $plural = '';
        if ($value > 1) {
            for ($i = 0; $i < strlen($phrase); ++$i) {
                if ($i == strlen($phrase) - 1) {
                    $plural .= ($phrase[$i] == 'y' && $phrase != 'day') ? 'ies' : (($phrase[$i] == 's' || $phrase[$i] == 'x' || $phrase[$i] == 'z' || $phrase[$i] == 'ch' || $phrase[$i] == 'sh') ? $phrase[$i].'es' : $phrase[$i].'s');
                } else {
                    $plural .= $phrase[$i];
                }
            }

            return $plural;
        }

        return $phrase;
    }

    /**
     * Get bytes from string.
     *
     * @param string
     *
     * @return string
     */
    public static function returnBytes($val)
    {
        //$val = trim($val);
        //$last = strtolower($val[strlen($val)-1]);
        //switch($last)
        //{
        //    case 'g':
        //    $val *= 1024;
        //    case 'm':
        //    $val *= 1024;
        //    case 'k':
        //    $val *= 1024;
        //}
        return $val;
    }

    /**
     * Get max upload file.
     *
     * @param string
     *
     * @return string
     */
    public static function maxFileUploadInBytes()
    {
        //select maximum upload size
        $max_upload = self::returnBytes(ini_get('upload_max_filesize'));
        //select post limit
        $max_post = self::returnBytes(ini_get('post_max_size'));
        //select memory limit
        $memory_limit = self::returnBytes(ini_get('memory_limit'));
        // return the smallest of them, this defines the real limit
        return min($max_upload, $max_post);
    }

    /**
     * Day of week select options.
     *
     * @param string
     *
     * @return array
     */
    public static function dayOfWeekSelectOptions()
    {
        return [
            ['value' => '1', 'text' => trans('messages.Monday')],
            ['value' => '2', 'text' => trans('messages.Tuesday')],
            ['value' => '3', 'text' => trans('messages.Wednesday')],
            ['value' => '4', 'text' => trans('messages.Thursday')],
            ['value' => '5', 'text' => trans('messages.Friday')],
            ['value' => '6', 'text' => trans('messages.Saturday')],
            ['value' => '7', 'text' => trans('messages.Sunday')],
        ];
    }

    /**
     * Day of week arrays.
     *
     * @param string
     *
     * @return array
     */
    public static function weekdaysArray()
    {
        $array = [];
        foreach (self::dayOfWeekSelectOptions() as $day) {
            $array[$day['value']] = $day['text'];
        }

        return $array;
    }

    /**
     * Month select options.
     *
     * @param string
     *
     * @return array
     */
    public static function monthSelectOptions()
    {
        return [
            ['value' => '1', 'text' => trans('messages.January')],
            ['value' => '2', 'text' => trans('messages.February')],
            ['value' => '3', 'text' => trans('messages.March')],
            ['value' => '4', 'text' => trans('messages.April')],
            ['value' => '5', 'text' => trans('messages.May')],
            ['value' => '6', 'text' => trans('messages.June')],
            ['value' => '7', 'text' => trans('messages.July')],
            ['value' => '8', 'text' => trans('messages.August')],
            ['value' => '9', 'text' => trans('messages.September')],
            ['value' => '10', 'text' => trans('messages.October')],
            ['value' => '11', 'text' => trans('messages.November')],
            ['value' => '12', 'text' => trans('messages.December')],
        ];
    }

    /**
     * Month array.
     *
     * @param string
     *
     * @return array
     */
    public static function monthsArray()
    {
        $array = [];
        foreach (self::monthSelectOptions() as $day) {
            $array[$day['value']] = $day['text'];
        }

        return $array;
    }

    /**
     * Week select options.
     *
     * @param string
     *
     * @return array
     */
    public static function weekSelectOptions()
    {
        return [
            ['value' => '1', 'text' => trans('messages.1st_week')],
            ['value' => '2', 'text' => trans('messages.2nd_week')],
            ['value' => '3', 'text' => trans('messages.3rd_week')],
            ['value' => '4', 'text' => trans('messages.4th_week')],
            ['value' => '5', 'text' => trans('messages.5th_week')],
        ];
    }

    /**
     * Week array.
     *
     * @param string
     *
     * @return array
     */
    public static function weeksArray()
    {
        $array = [];
        foreach (self::weekSelectOptions() as $day) {
            $array[$day['value']] = $day['text'];
        }

        return $array;
    }

    /**
     * Month select options.
     *
     * @param string
     *
     * @return array
     */
    public static function dayOfMonthSelectOptions()
    {
        $arr = [];
        for ($i = 1; $i < 32; ++$i) {
            $arr[] = ['value' => $i, 'text' => $i];
        }

        return $arr;
    }

    /**
     * Quota time unit options.
     *
     * @return array
     */
    public static function timeUnitOptions()
    {
        return [
            ['value' => 'minute', 'text' => trans('messages.minute')],
            ['value' => 'hour', 'text' => trans('messages.hour')],
            ['value' => 'day', 'text' => trans('messages.day')],
            ['value' => 'week', 'text' => trans('messages.week')],
            ['value' => 'month', 'text' => trans('messages.month')],
            ['value' => 'year', 'text' => trans('messages.year')],
        ];
    }

    /**
     * Get php paths select options.
     *
     * @param timestamp
     *
     * @return string
     */
    public static function phpPathsSelectOptions($paths)
    {
        $options = [];

        foreach ($paths as $path) {
            $options[] = [
                'text' => $path,
                'value' => $path,
            ];
        }

        $options[] = [
            'text' => trans('messages.php_bin_manual'),
            'value' => 'manual',
        ];

        return $options;
    }

    /**
     * Check php bin path is valid.
     *
     * @param string
     *
     * @return bool
     */
    public static function checkPHPBinPath($path)
    {
        $result = '';
        try {
            if (!file_exists($path) || !is_executable($path)) {
                return $result;
            }
        } catch (\Exception $ex) {
            // open_basedir in effect
        }

        if (exec_enabled()) {
            $exec_script = $path.' '.base_path().'/php_bin_test.php 2>&1';
            $result = exec($exec_script, $output);
        } else {
            $result = 'ok';
        }

        return $result;
    }

    /**
     * Get available System Background Methods Select Options.
     *
     * @param timestamp
     *
     * @return string
     */
    public static function availableSystemBackgroundMethodSelectOptions()
    {
        $options = [
            [
                'text' => trans('messages.database_job_type'),
                'value' => 'database',
                'description' => trans('messages.database_job_type_desc'),
            ],
        ];

        if (true) {
            $options[] = [
                'text' => trans('messages.async_job_type'),
                'description' => trans('messages.async_job_type_desc'),
                'value' => 'async',
                'disabled' => true, //exec_enabled(),
                'tooltip' => (!exec_enabled() ? 'Your server does not support async' : ''),
            ];
        }

        return $options;
    }

    /**
     * Control cronjob update request.
     *
     * @param timestamp
     *
     * @return string
     */
    public static function cronjobUpdateController($request, $controller)
    {

        // Suggestion paths
        $paths = [
            '/usr/local/bin/php',
            '/usr/bin/php',
            '/bin/php',
            '/usr/bin/php7',
            '/usr/bin/php7.3',
            '/usr/bin/php7.4',
            '/usr/bin/php73',
            '/usr/bin/php74',
            '/usr/bin/php8',
            '/usr/bin/php8.0',
            '/usr/bin/php8.1',
            '/usr/bin/php80',
            '/usr/bin/php81',
            '/opt/plesk/php/7.3/bin/php',
            '/opt/plesk/php/7.4/bin/php',
            '/opt/plesk/php/8.0/bin/php',
            '/opt/plesk/php/8.1/bin/php',
        ];

        // try to detect system's PHP CLI
        if (exec_enabled()) {
            try {
                $paths = array_unique(array_merge($paths, explode(' ', exec('whereis php'))));
            } catch (\Exception $e) {
                // @todo: system logging here
                echo $e->getMessage();
            }
        }

        // validate detected / default PHP CLI
        // Because array_filter() preserves keys, you should consider the resulting array to be an associative array even if the original array had integer keys for there may be holes in your sequence of keys. This means that, for example, json_encode() will convert your result array into an object instead of an array. Call array_values() on the result array to guarantee json_encode() gives you an array.
        $paths = array_values(array_filter($paths, function ($path) {
            $checked = true;
            try {
                $checked = $checked && is_executable($path);
            } catch (\Exception $ex) {
                // in case of open_basedir, just throw skip it
                $checked = true;
            }

            return $checked && preg_match("/php[0-9\.a-z]{0,3}$/i", $path);
        }));

        $rules = [];

        // Current path
        $queue_driver = config('queue.default');
        $php_bin_path = empty($paths) ? 'manual' : $paths[0];
        $php_bin_path_value = empty($paths) ? '' : $paths[0];

        $setting_php_bin_path = \Acelle\Model\Setting::get('php_bin_path');
        if (!empty($setting_php_bin_path)) {
            if (in_array($setting_php_bin_path, $paths)) {
                $php_bin_path = $setting_php_bin_path;
            } else {
                $php_bin_path = 'manual';
            }
            $php_bin_path_value = $setting_php_bin_path;
        }

        if (!empty($request->old())) {
            $php_bin_path = $request->old()['php_bin_path'];
            $php_bin_path_value = $request->old()['php_bin_path_value'];
            $queue_driver = $request->old()['queue_driver'];
        }

        // create remote token if empty
        if (empty(\Acelle\Model\Setting::get('remote_job_token'))) {
            \Acelle\Model\Setting::set('remote_job_token', str_random(60));
        }

        $request->session()->forget('cron_jobs');
        $error = '';
        $valid = false;
        if ($request->isMethod('post')) {
            $php_bin_path = $request->php_bin_path;
            $php_bin_path_value = $request->php_bin_path_value;
            $queue_driver = $request->queue_driver;

            // If type == database
            if ($request->queue_driver == 'database') {
                $rules = [
                    'php_bin_path_value' => 'required',
                    'queue_driver' => 'required',
                ];

                // Check valid path
                $check = \Acelle\Library\Tool::checkPHPBinPath($php_bin_path_value);
                if ($check != 'ok') {
                    $rules['php_bin_path_invalid'] = 'required';
                }

                $controller->validate($request, $rules);

                \Acelle\Model\Setting::set('php_bin_path', $php_bin_path_value);

                $valid = true;
            }

            $request->session()->put('cron_jobs', true);

            // Update .env
            if (in_array($queue_driver, ['database', 'async']) && config('queue.default') != $queue_driver) {
                \Acelle\Helpers\write_env('QUEUE_DRIVER', $queue_driver);
            }

            if ($request->queue_driver == 'async') {
                return 'done';
            }

            $request->session()->flash('alert-success', trans('messages.setting.updated'));
        }

        return [
            'step' => 5,
            'current' => 5,
            'php_paths' => $paths,
            'php_bin_path' => $php_bin_path,
            'php_bin_path_value' => $php_bin_path_value,
            'rules' => $rules,
            'error' => $error,
            'queue_driver' => $queue_driver,
            'valid' => $valid,
        ];
    }

    /**
     * Show re-captcha in views.
     *
     * @return string
     */
    public static function showReCaptcha($errors = null)
    {
        ?>
            <div class="recaptcha-box">
                <script src='https://www.google.com/recaptcha/api.js?hl=<?php echo language_code() ?>'></script>
                <div class="g-recaptcha" data-sitekey="6LfyISoTAAAAABJV8zycUZNLgd0sj-sBFjctzXKw"></div>
                <?php if (isset($errors) && $errors->has('recaptcha_invalid')) {
            ?>
                    <span class="help-block text-danger">
                        <strong><?php echo $errors->first('recaptcha_invalid'); ?></strong>
                    </span>
                <?php
        } ?>
            </div>
        <?php
    }

    /**
     * Check re-captcha success.
     *
     * @return bool
     */
    public static function checkReCaptcha($request)
    {
        if (!isset($request->all()['g-recaptcha-response'])) {
            return false;
        }

        // Check recaptch
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $res = $client->post('https://www.google.com/recaptcha/api/siteverify', ['verify' => false, 'form_params' => [
            'secret' => '6LfyISoTAAAAAC0hJ916unwi0m_B0p7fAvCRK4Kp',
            'remoteip' => $request->ip(),
            'response' => $request->all()['g-recaptcha-response'],
        ]]);

        return json_decode($res->getBody(), true)['success'];
    }

    /**
     * Format price.
     *
     * @param string
     *
     * @return string
     */
    public static function format_price($price, $format = '{PRICE}')
    {
        return str_replace('{PRICE}', number_with_delimiter($price), $format);
    }

    /**
     * Check current view if exist.
     *
     * @return bool
     */
    public static function currentView()
    {
        return \Request::is('admin*') ? 'backend' : 'frontend';
    }

    /**
     * Get Directory Size.
     *
     * @var string
     */
    public static function getDirectorySize($path)
    {
        $bytestotal = 0;
        $path = realpath($path);
        if ($path !== false) {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)) as $object) {
                $bytestotal += $object->getSize();
            }
        }

        return $bytestotal;
    }

    /**
     * Check email is valid.
     *
     * @var string
     */
    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function zip($folder, $zipfile)
    {
        // Trick: add a trailing slash "/" to the path, it is needed for sre_replace to work properly
        $folder = join_paths($folder, '/');

        // IMPORTANT: $folder  must be an absolute path
        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($zipfile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folder),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = str_replace($folder, "", $filePath);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
    }
}
