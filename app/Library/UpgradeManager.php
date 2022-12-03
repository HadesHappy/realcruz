<?php

/**
 * UpgradeManager class.
 *
 * Tool for upgrading the entire system source
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
use Illuminate\Support\Facades\Log as LaravelLog; // something wrong, cannot use the default name Log
use Illuminate\Support\Facades\File;
use Acelle\Library\Facades\Hook;
use Acelle\Model\Language;
use function Acelle\Helpers\updateTranslationFile;

class UpgradeManager
{
    protected $source;
    protected $target;

    protected $allTranslationFiles;

    public const META_FILE = 'meta.json';

    /**
     * Constructor, specify the source, target and load the meta information.
     */
    public function __construct()
    {
        $this->source = storage_path('tmp/patch');
        $this->target = base_path();
        $this->allTranslationFiles = $this->findTranslationFiles();

        // deletect all language files
    }

    private function findTranslationFiles()
    {
        $allTranslationFiles = [];
        foreach (Language::all() as $lang) {
            foreach (Hook::execute('add_translation_file') as $source) {
                $path = join_paths($source['translation_folder'], $lang->code, $source['file_name']);
                $allTranslationFiles[] = $path;
            }
        }

        return $allTranslationFiles;
    }

    /**
     * Constructor, specify the source, target and load the meta information.
     */
    public function load($path)
    {
        // Check WRITE permission
        $this->cleanup();

        try {
            // Extract the zip file
            $old = umask(0);
            $zip = new ZipArchive();
            $res = $zip->open($path);
            if ($res === true) {
                $zip->extractTo($this->source);
                $zip->close();
                umask($old);

                // test the patch, throw an exception in case meta.json does not exist
                $this->validate();
            } else {
                umask($old);
                LaravelLog::error('Cannot open zip file '.$path.' with error code '.$res);
                throw new \Exception('Invalid upgrade package');
            }
        } catch (\Exception $e) {
            $this->rm($this->source);
            throw $e;
        }
    }

    /**
     * Read the meta data from a patch package.
     */
    public function getNewVersion()
    {
        if ($this->isNewVersionAvailable()) {
            return $this->getMetaInfo()['version'];
        } else {
            return;
        }
    }

    /**
     * Get last supported version for upgrade.
     */
    public function getLastSupportedVersion()
    {
        if ($this->isNewVersionAvailable()) {
            return $this->getMetaInfo()['last_supported'];
        } else {
            return;
        }
    }

    /**
     * Read the meta data from a patch package.
     */
    public function cleanup()
    {
        // Check WRITE permission
        if (!$this->isWritable($this->source)) {
            throw new \Exception("Cannot write to folder {$this->source}");
        }

        // Clean up the target folder
        $this->rm($this->source);
    }

    /**
     * Read the meta data from a patch package.
     */
    public function validate()
    {
        $meta = $this->getMetaInfo();
        if (version_compare($this->getNewVersion(), $this->getCurrentVersion(), '=')) {
            throw new \Exception(sprintf('The version you uploaded is the same as the current one (%s)', $this->getNewVersion()));
        }

        if (version_compare($this->getNewVersion(), $this->getCurrentVersion(), '<')) {
            throw new \Exception(sprintf('The version you uploaded (%s) is older than the current one (%s)', $this->getNewVersion(), $this->getCurrentVersion()));
        }

        if (version_compare($this->getLastSupportedVersion(), $this->getCurrentVersion(), '>')) {
            throw new \Exception(sprintf('You are on a version that is not supported by this update, last supported version is %s', $this->getLastSupportedVersion()));
        }

        // DRYRUN to see if there is any error
        $this->test();
    }

    /**
     * Read the meta data from a patch package.
     */
    private function getMetaInfo()
    {
        $path = join_paths($this->source, self::META_FILE);
        if (!file_exists($path)) {
            // Clean up invalid package (without meta file)
            // Then raise an exception
            // So that user will only see this message once
            $this->cleanup();

            // Ooops!
            throw new \Exception('NOMETA: Unknown package format (deleted)');
        }

        return json_decode(file_get_contents($path), true);
    }

    /**
     * Actually run the upgrade process.
     */
    public function run($test = false)
    {
        $this->refreshConfig();

        $old = umask(0);

        try {
            if ($test) {
                LaravelLog::info('Start upgrading (test)');
            } else {
                LaravelLog::info('Start upgrading');
            }

            if (!$test) {
                // set umask(0) and back up the current umask
                $old = umask(0);
            }

            $errors = [];
            $meta = $this->getMetaInfo();
            $updates = $meta['updated'];
            $deletes = $meta['deleted'];
            $packages = $meta['packages'];
            $dirs = $meta['dirs'];
            $langFiles = [];

            // new or updated entire directories
            foreach ($dirs as $dir) {
                $source = join_paths($this->source, $dir);
                $target = join_paths($this->target, $dir);
                if ($test) {
                    if (!$this->isWritable($target)) {
                        $errors[] = $target;
                    }
                } else {
                    LaravelLog::info("REPLACE DIRECTORY {$dir}");
                    $this->rm($target);
                    $this->copy($source, $target);
                }
            }

            // new or updated files
            foreach ($updates as $file) {
                $source = join_paths($this->source, $file);
                $target = join_paths($this->target, $file);

                // File is deleted in build.sh script
                if (!File::exists($source)) {
                    continue;
                }

                if ($test) {
                    if (!$this->isWritable($target)) {
                        $errors[] = $target;
                    }
                } else {
                    if ($this->isTranslationFile($target)) {
                        // Make sure the 'resources/lang/default/' folder is already updated
                        LaravelLog::info('UPDATE translation file: '.$file);
                        $this->mergeTranslationFileWithoutOverwritting($target, $source);
                    } else {
                        LaravelLog::info('REPLACE file: '.$file);
                        $this->copy($source, $target);
                    }
                }
            }

            // deleted files
            foreach ($deletes as $file) {
                $target = join_paths($this->target, $file);

                if ($test) {
                    if (!$this->isWritable($target)) {
                        $errors[] = $target;
                    }
                } else {
                    LaravelLog::info("DELETE file {$file}");
                    @unlink($target);
                }
            }

            // new or updated packages
            foreach ($packages as $dir) {
                $source = join_paths($this->source, 'vendor', $dir);
                $target = join_paths($this->target, 'vendor', $dir);
                if ($test) {
                    if (!$this->isWritable($target)) {
                        $errors[] = $target;
                    }
                } else {
                    LaravelLog::info("REPLACE package {$dir}");
                    $this->rm($target);
                    $this->copy($source, $target);
                }
            }

            // just finish for test mode
            if ($test) {
                return $errors;
            }

            // cleanup
            LaravelLog::info('Cleaning up');
            $this->cleanup();

            if (!$test) {
                // restore the umask
                umask($old);
            }

            return true;
        } catch (\Exception $e) {
            if (!$test) {
                // restore the umask
                umask($old);
            }
            throw $e;
        }
    }

    public function refreshConfig()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('route:clear');
            \Artisan::call('view:clear');
        } catch (\Exception $e) {
            // something wrong here, just ignore
        }

        // GO AFTER ARTISAN CALL
        LaravelLog::info('DELETE cached config files');
        $files = [
            'bootstrap/cache/packages.php',
            'bootstrap/cache/config.php',
            'bootstrap/cache/services.php',
            'bootstrap/cache/compiled.php'
        ];

        foreach ($files as $file) {
            $path = base_path($file);
            if (file_exists($path)) {
                $this->rm($path);
            }
        }
    }

    /**
     * Merge .env files.
     */
    public function mergeEnv($updatefile)
    {
        if (!file_exists($updatefile)) {
            return;
        }

        // Important: load all available env variables (in case they are erased by cache:clear)
        \Artisan::call('config:cache');

        $updatedb = file($updatefile);

        $rexp = '/\s*=\s*/';
        $mainkeys = array_keys($_ENV);

        foreach ($updatedb as $updaterecord) {
            if (empty(trim($updaterecord))) {
                continue;
            }

            if (preg_match('/^\s*#/', $updaterecord)) {
                // skip commented line with leading #
                continue;
            }

            list($updatekey, $updatevalue) = preg_split($rexp, $updaterecord);
            // echo "Checking $updatekey\n";
            if (!in_array($updatekey, $mainkeys)) {
                $updatevalue = stripslashes(trim(trim($updatevalue), '"'));
                \Acelle\Helpers\write_env($updatekey, $updatevalue);
            }
        }

        // Important: cache configs AGAIN to reload the settings from .env
        \Artisan::call('config:clear');
        \Artisan::call('config:cache');
    }

    /**
     * Test the upgrade process (DRY-RUN).
     */
    public function test()
    {
        return $this->run(true);
    }

    /**
     * Get current app version.
     */
    public function getCurrentVersion()
    {
        return trim(file_get_contents(base_path('VERSION')));
    }

    /**
     * Check if new version is available.
     */
    public function isNewVersionAvailable()
    {
        return file_exists($this->source);
    }

    /**
     * Check if an existing file is writable or a new path can be created.
     *
     * @input string file path
     * @output boolean
     */
    private function isWritable($path)
    {
        if (is_writable($path)) {
            return true;
        } elseif (!file_exists($path) && $this->canCreateFile($path)) {
            return true;
        } else {
            // file exists but not writable
            // file not exist nor creatable
            return false;
        }
    }

    /**
     * Check if the specified path can be created.
     *
     * @output boolean
     */
    private function canCreateFile($path)
    {
        $a = explode(DIRECTORY_SEPARATOR, $path);
        $parent = null;
        for ($i = 0; $i < sizeof($a); $i += 1) {
            $tmppath = implode(DIRECTORY_SEPARATOR, array_slice($a, 0, $i));

            if (empty($tmppath)) {
                continue;
            }

            try {
                if (!file_exists($tmppath)) {
                    break;
                } else {
                    $parent = $tmppath;
                }
            } catch (\Exception $ex) {
                LaravelLog::warning($path.' not in open_basedir: '.ini_get('open_basedir'));
                $parent = $tmppath;
            }
        }

        return is_writable($parent);
    }

    /**
     * Delete a directory recursively.
     */
    private function rm($src)
    {
        if (!file_exists($src)) {
            return;
        }

        if (!is_dir($src)) {
            unlink($src);

            return;
        }

        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src.'/'.$file;
                if (is_dir($full)) {
                    $this->rm($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

    /**
     * Copy file or directory to a destination (always REMOVE and REPLACE the destination).
     * Make sure parent directories are always created, similarly to "cp -p"
     *
     */
    public function copy($src, $dst)
    {
        if (File::exists($dst)) {
            // Delete the file or link or directory
            if (is_link($dst) || is_file($dst)) {
                // To preserve $dst's permission
                // File::delete($dst);
            } else {
                File::deleteDirectory($dst);
            }
        } else {
            // Make sure the PARENT directory exists
            $dirname = pathinfo($dst)['dirname'];
            if (!File::exists($dirname)) {
                File::makeDirectory($dirname, 0777, true, true);
            }
        }

        // if source is a file, just copy it
        if (File::isFile($src)) {
            File::copy($src, $dst);
        } else {
            File::copyDirectory($src, $dst);
        }
    }

    /**
     * Check if the provided file is a language file.
     */
    public function isTranslationFile($path) // absolute path
    {
        return in_array($path, $this->allTranslationFiles);
    }

    /**
     * Upgrade all existing language packages using the provided file
     */
    public function mergeTranslationFileWithoutOverwritting($target, $source)
    {
        updateTranslationFile($target, $source, $overwrite = false, $sort = true);
    }
}
