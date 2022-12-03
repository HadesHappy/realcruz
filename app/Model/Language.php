<?php

/**
 * Language class.
 *
 * Model class for languages
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
use Acelle\Library\Facades\Hook;
use Acelle\Library\Traits\HasUid;
use File;
use Exception;
use DB;

class Language extends Model
{
    use HasUid;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    /**
     * Get users.
     *
     * @return mixed
     */
    public function users()
    {
        return $this->hasMany('Acelle\Model\User');
    }

    /**
     * Customer association.
     *
     * @return mixed
     */
    public function customers()
    {
        return $this->hasMany('Acelle\Model\Customer');
    }

    /**
     * Admin association.
     *
     * @return mixed
     */
    public function admins()
    {
        return $this->hasMany('Acelle\Model\Admin');
    }

    /**
     * Language folder path.
     *
     * @return string
     */
    public function languageDir()
    {
        return resource_path(join_paths('lang', $this->code));
    }

    public static function getDirWhichNewLanguageCopyFrom()
    {
        return base_path('resources/lang/default');
    }

    public function scopeActive($query)
    {
        $query->where('status', '=', self::STATUS_ACTIVE);
    }

    /**
     * Get select options.
     *
     * @return array
     */
    public static function getSelectOptions()
    {
        $options = self::active()->get()->map(function ($item) {
            return ['value' => $item->id, 'text' => $item->name];
        });

        return $options;
    }

    /**
     * Search items.
     *
     * @return collect
     */
    public function scopeSearch($query, $keyword)
    {
        // Keyword
        if (!empty(trim($keyword))) {
            $keyword = trim($keyword);
            foreach (explode(' ', $keyword) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('languages.name', 'like', '%'.$keyword.'%')
                        ->orwhere('languages.code', 'like', '%'.$keyword.'%')
                        ->orwhere('languages.region_code', 'like', '%'.$keyword.'%');
                });
            }
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'region_code',
    ];

    /**
     * Get validation rules.
     *
     * @return object
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required|unique:languages,code,'.$this->id,
        ];
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', '=', true);
    }

    /**
     * Get is default language.
     *
     * @var object
     */
    public static function getFirstDefaultLanguage()
    {
        return self::default()->first();
    }

    /**
     * Get locale array from file.
     *
     * @var array
     */
    public function getLocaleArrayFromFile($filename)
    {
        clearstatcache();
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate(join_paths($this->languageDir(), $filename.'.php'));
        }

        $arr = self::fileToArray(join_paths($this->languageDir(), $filename.'.php'));
        return $arr;
    }

    /**
     * Read locale file.
     *
     * @var text
     */
    public function readLocaleFile($filename)
    {
        $text = \File::get(join_paths($this->languageDir(), $filename.'.php'));

        return $text;
    }

    /**
     * Read locale file.
     *
     * @var text
     */
    public function localeToYaml($filename)
    {
        $text = $this->readLocaleFile($filename);

        return yaml_parse($text);
    }

    /**
     * Update language file from yaml.
     *
     * @var text
     */
    public function updateFromYaml($filename, $yaml)
    {
        self::yamlToFile(join_paths($this->languageDir(), $filename.'.php'), $yaml);
    }

    /**
     * Update language file from yaml.
     *
     * @var text
     */
    public function getBuilderLang()
    {
        return include join_paths($this->languageDir(), 'builder.php');
    }

    /**
     * all language code.
     *
     * @return array
     */
    public static function languageCodes()
    {
        $arr = config('languages');

        $result = [];
        foreach ($arr as $key => $name) {
            $result[] = [
                'text' => strtoupper($key).' / '.$name,
                'value' => $key,
            ];
        }

        return $result;
    }

    /**
     * Disable language.
     *
     * @return array
     */
    public function disable()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->save();
    }

    /**
     * Enable language.
     *
     * @return array
     */
    public function enable()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->save();
    }

    public static function fileToArray($pathToFile)
    {
        return \File::getRequire($pathToFile);
    }

    public static function arrayToYaml($array)
    {
        return \Yaml::dump($array);
    }

    public static function fileToYaml($path)
    {
        return self::arrayToYaml(self::fileToArray($path));
    }

    public static function yamlToFile($pathToFile, $yaml)
    {
        $content = '<?php return '.var_export(\Yaml::parse($yaml), true).' ?>';
        $bytes_written = \File::put($pathToFile, $content);
    }

    public function getAllLanguageFiles()
    {
        // This function return all translation files for the current language
        // with additional information
        // For example:
        //     [
        //           [
        //               'file_name' => 'messages.php'
        //               'path' => '/acellemail/resources/lang/{en}/messages.php'
        //               'type' => default | plugin
        //               ''
        //           ]
        //
        //     ]

        $paths = [];

        $files = Hook::execute('add_translation_file');
        foreach ($files as $file) {
            $path = join_paths($file['translation_folder'], $this->code, $file['file_name']);

            if (in_array($file['id'], array_keys($paths))) {
                throw new \Exception('Translation file id already exists: ' . $file['id']);
            }

            if (!file_exists($path)) {
                // Trick, should be removed soon
                $this->createTranslationFile($file);
            }

            $paths[$file['id']] = [
                'id' => $file['id'],
                'type' => isset($file['type']) ? $file['type'] : 'plugin',
                'path' => $path,
                'file_title' => $file['file_title'],
            ];
        }

        return $paths;
    }

    public function getLanguageFilesByType($type)
    {
        $langFiles = $this->getAllLanguageFiles();
        foreach ($langFiles as $key => $langFile) {
            if ($langFile['type'] != $type) {
                unset($langFiles[$key]);
            }
        }

        return $langFiles;
    }

    public function getLanguageFileOptions()
    {
        $arr = [];
        foreach ($this->getAllLanguageFiles() as $key => $langFile) {
            $arr[] = ['value' => $langFile['id'], 'text' => $langFile['file_title']];
            ;
        }

        return $arr;
    }

    public static function newDefaultLanguage()
    {
        $language = new self();
        $language->status = self::STATUS_ACTIVE;

        return $language;
    }

    // @todo 'dump' is just an alias for many tasks that may be involved
    // + create missing translation files
    // + update existing translation files from its original source
    // + more
    public static function dump()
    {
        // Update or create translation files
        foreach (self::get() as $language) {
            $language->createOrUpdateTranslationFiles();
        }
    }

    public function createTranslationFile($source)
    {
        if (!array_key_exists('master_translation_file', $source)) {
            throw new Exception('[master_translation_file] is not available for '.$source['file_name']);
        }

        $originFile = $source['master_translation_file'];

        // Other files
        //     + resources / lang / ja / messages.php
        $langFile = join_paths($source['translation_folder'], $this->code, $source['file_name']);

        if (!file_exists($originFile)) {
            throw new Exception('Original translation file does not exist: '.$originFile.'. Make sure it is registered correctly. Or, if it is a local development environment, just make a "default" folder by copying "resources/lang/en" ==> "resources/lang/default": '.$originFile);
        }

        // If the originFile is also the current language's file
        // Notice that realpath() is used to remove ../../ in file path, before comparing
        if (!file_exists($langFile)) {
            // Create language's file by copying from the original file
            \Acelle\Helpers\pcopy($originFile, $langFile);
        }

        return [$originFile, $langFile];
    }

    public function createOrUpdateTranslationFiles()
    {
        foreach (Hook::execute('add_translation_file') as $source) {
            list($originFile, $langFile) = $this->createTranslationFile($source);

            if (realpath($originFile) != realpath($langFile)) {
                \Acelle\Helpers\updateTranslationFile($langFile, $originFile, $overwrite = false, $sort = true);
            }
        }
    }

    public static function createFromArray($attributes)
    {
        $language = self::newDefaultLanguage();

        $language->fill($attributes);
        $language->status = self::STATUS_INACTIVE;

        // make validator
        $validator = \Validator::make($attributes, $language->rules());

        // redirect if fails
        if ($validator->fails()) {
            return [$language, $validator];
        }

        DB::transaction(function () use (&$language) {
            // save
            $language->save();

            // Create translation files for this newly created language
            $language->createOrUpdateTranslationFiles();
        });

        return [$language, true];
    }

    public function updateFromRequest($request)
    {
        // make validator
        $validator = \Validator::make($request->all(), $this->rules());

        // redirect if fails
        if ($validator->fails()) {
            return $validator;
        }

        // rename locale folder
        if ($this->code != $request->code) {
            rename(base_path("resources/lang/") . $this->code, base_path("resources/lang/") . $request->code);
        }

        $this->fill($request->all());

        // save
        $this->save();

        return true;
    }

    public function deleteAndCleanup()
    {
        // Change deleting language's users to the default langauge
        $default_language = self::getFirstDefaultLanguage();

        if (!$default_language) {
            throw new \Exception('Something went wrong! Can not find the default language.');
        }

        $this->customers()->update(['language_id' => $default_language->id]);
        $this->admins()->update(['language_id' => $default_language->id]);

        // delete language folder
        $des = $this->languageDir();
        if (file_exists($des)) {
            \Acelle\Library\Tool::xdelete($des);
        }

        $this->delete();
    }

    public function translateFile($fileId, $content)
    {
        $file = $this->findFileById($fileId);

        // make validator
        $validator = \Validator::make(['content' => $content], [
            'content' => 'required',
        ]);

        // test amazon api connection
        $validator->after(function ($validator) use ($file, $content) {
            try {
                var_export(\Yaml::parse($content), true);
            } catch (\Exception $e) {
                $validator->errors()->add('content', $e->getMessage());
            }
        });

        // redirect if fails
        if ($validator->fails()) {
            return [$file, $validator];
        }

        // save
        self::yamlToFile($file['path'], $content);

        \Artisan::call('cache:clear');

        return [$file, $validator];
    }

    public function findFileById($id)
    {
        if (!isset($this->getAllLanguageFiles()[$id])) {
            throw new \Exception('Can not find translation file with id: ' . $id);
        }

        return $this->getAllLanguageFiles()[$id];
    }

    public function getDefaultFile()
    {
        $files = $this->getAllLanguageFiles();
        return array_shift($files);
    }

    public function upload($request)
    {
        // make validator
        $validator = \Validator::make($request->all(), [
            'file' => 'required',
        ]);

        // test amazon api connection
        $validator->after(function ($validator) use ($request) {
            $zip = new \ZipArchive();

            // check if file is zip achive
            $file_ext = $request->file('file')->guessExtension();
            if ($file_ext != 'zip') {
                $validator->errors()->add('content', 'Upload file is not zip file');
                return;
            }

            // move file to temp place
            $tmp_path = storage_path('tmp');
            $file_name = 'language-package';
            $request->file('file')->move($tmp_path, $file_name);

            // after moving, request['file'] will no longer be there
            $rules = [];
            $tmp_zip = storage_path("tmp/{$file_name}");
            $openZip = $zip->open($tmp_zip, \ZipArchive::CREATE);

            // read zip file check if zip archive invalid
            if ($openZip !== true) {
                $validator->errors()->add('content', 'Upload file is not valide archive file');
                return;
            }

            // unzip template archive and remove zip file
            $zip->extractTo($this->languageDir());
            $zip->close();
            unlink($tmp_zip);
        });

        return $validator;
    }
}
