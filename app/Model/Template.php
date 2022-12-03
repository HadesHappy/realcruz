<?php

/**
 * Template class.
 *
 * Model class for template
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
use Validator;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Acelle\Library\Traits\HasUid;
use Illuminate\Validation\ValidationException;
use KubAT\PhpSimple\HtmlDomParser;
use Acelle\Library\Tool;
use Acelle\Library\StringHelper;
use DOMDocument;
use File;
use Exception;
use Closure;
use League\Pipeline\PipelineBuilder;
use Acelle\Library\HtmlHandler\TransformWidgets;
use function Acelle\Helpers\getAppHost;

class Template extends Model
{
    use HasUid;

    public const BUILDER_ENABLED = true;
    public const BUILDER_DISABLED = false;

    public const TYPE_EMAIL = 'email';
    public const TYPE_POPUP = 'popup';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'name', 'content', 'builder', 'is_default', 'theme', 'type'
    ];

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

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
        return $this->belongsTo('Acelle\Model\Admin');
    }

    /**
     * The template that belong to the categories.
     */
    public function categories()
    {
        return $this->belongsToMany('Acelle\Model\TemplateCategory', 'templates_categories', 'template_id', 'category_id');
    }

    /**
     * Search.
     *
     * @return collect
     */
    public function scopeCategoryUid($query, $uid)
    {
        $category = \Acelle\Model\TemplateCategory::findByUid($uid);
        // Category
        if ($category) {
            $query = $query->whereHas('categories', function ($q) use ($category) {
                $q->whereIn('template_categories.id', [$category->id]);
            });
        }
    }

    // Templates that are not associated to any email or campaign
    public function scopeNotPreserved($query)
    {
        $query->whereNotIn('id', function ($q) {
            $q->select('template_id')->from('emails')->whereNotNull('template_id');
        });

        $query->whereNotIn('id', function ($q) {
            $q->select('template_id')->from('campaigns')->whereNotNull('template_id');
        });

        $query->whereNotIn('id', function ($q) {
            $q->select('template_id')->from('forms')->whereNotNull('template_id');
        });
    }

    // Default templates
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Search.
     *
     * @return collect
     */
    public function scopeSearch($query, $keyword)
    {
        // Keyword
        if (!empty($keyword)) {
            $query = $query->where('name', 'like', '%'.trim($keyword).'%');
        }
    }

    /**
     * Customer templates.
     *
     * @return collect
     */
    public function scopeCustom($query)
    {
        $query = $query->whereNotNull('customer_id');
    }

    /**
     * Public/Gallery templates.
     *
     * @return collect
     */
    public static function scopeShared($query)
    {
        $query = $query->whereNull('customer_id');
    }

    public static function scopeEmail($query)
    {
        $query = $query->where('type', '=', self::TYPE_EMAIL);
    }

    public static function scopePopup($query)
    {
        $query = $query->where('type', '=', self::TYPE_POPUP);
    }

    /**
     * Template tags.
     *
     * All availabel template tags
     */
    public static function tags($list = null)
    {
        $tags = [];

        $tags[] = ['name' => 'SUBSCRIBER_EMAIL', 'required' => false];

        // List field tags
        if (isset($list)) {
            foreach ($list->fields as $field) {
                if ($field->tag != 'EMAIL') {
                    $tags[] = ['name' => 'SUBSCRIBER_'.$field->tag, 'required' => false];
                }
            }
        }

        $tags = array_merge($tags, [
            ['name' => 'UNSUBSCRIBE_URL', 'required' => false],
            ['name' => 'SUBSCRIBER_UID', 'required' => false],
            ['name' => 'WEB_VIEW_URL', 'required' => false],
            ['name' => 'UPDATE_PROFILE_URL', 'required' => false],
            ['name' => 'CAMPAIGN_NAME', 'required' => false],
            ['name' => 'CAMPAIGN_UID', 'required' => false],
            ['name' => 'CAMPAIGN_SUBJECT', 'required' => false],
            ['name' => 'CAMPAIGN_FROM_EMAIL', 'required' => false],
            ['name' => 'CAMPAIGN_FROM_NAME', 'required' => false],
            ['name' => 'CAMPAIGN_REPLY_TO', 'required' => false],
            ['name' => 'CURRENT_YEAR', 'required' => false],
            ['name' => 'CURRENT_MONTH', 'required' => false],
            ['name' => 'CURRENT_DAY', 'required' => false],
            ['name' => 'CONTACT_NAME', 'required' => false],
            ['name' => 'CONTACT_COUNTRY', 'required' => false],
            ['name' => 'CONTACT_STATE', 'required' => false],
            ['name' => 'CONTACT_CITY', 'required' => false],
            ['name' => 'CONTACT_ADDRESS_1', 'required' => false],
            ['name' => 'CONTACT_ADDRESS_2', 'required' => false],
            ['name' => 'CONTACT_PHONE', 'required' => false],
            ['name' => 'CONTACT_URL', 'required' => false],
            ['name' => 'CONTACT_EMAIL', 'required' => false],
            ['name' => 'LIST_NAME', 'required' => false],
            ['name' => 'LIST_SUBJECT', 'required' => false],
            ['name' => 'LIST_FROM_NAME', 'required' => false],
            ['name' => 'LIST_FROM_EMAIL', 'required' => false],
        ]);

        return $tags;
    }

    /**
     * Display creator name.
     *
     * @return string
     */
    public function displayCreatorName()
    {
        return is_object($this->admin) ? $this->admin->user->displayName() : (is_object($this->customer) ? $this->customer->user->displayName() : '');
    }

    /**
     * Contain category
     *
     * @return void
     */
    public function hasCategory($category)
    {
        return $this->categories()->where('template_categories.id', $category->id)->exists();
    }

    /**
     * Add category
     *
     * @return void
     */
    public function addCategory($category)
    {
        if (!$this->hasCategory($category)) {
            $this->categories()->attach($category->id);
        }
    }

    /**
     * Remove category
     *
     * @return void
     */
    public function removeCategory($category)
    {
        if ($this->hasCategory($category)) {
            $this->categories()->detach($category->id);
        }
    }

    /**
     * Copy new template.
     */
    public function copy($attributes = [])
    {
        $copy = new self();

        // UID and Customer ID must be present first, in order to create directory and to transformURL
        $copy->generateUid();
        // IMPORTANT
        // copy a shared template to customer --> then customer_id must be present
        // copy a customer template to customer --> either present or null is ok
        // copy a shared template to another shared template --> then customer_id must be empty (of course)
        $copy->customer_id = isset($attributes['customer_id']) ? $attributes['customer_id'] : $this->customer_id;

        // Copy content
        // The two steps below are important
        // First: convert $this' asset base URL to relative URL (for assets)
        // Then: transform relative URLS for $copy
        $copy->content = $this->getContentWithUntransformedAssetsUrls();
        $copy->transformAssetsUrls(); // important

        // copy theme
        $copy->theme = $this->theme;

        // copy type
        $copy->type = $this->type;

        // Copy flags
        $copy->builder = $this->builder;
        $copy->is_default = false; // no longer default

        // Overwrite attributes :name
        $copy->name = isset($attributes['name']) ? $attributes['name'] : $this->name;

        // Then save, generate UID, created_at, updated_at
        $copy->save();

        // Copy directory
        \Acelle\Helpers\pcopy($this->getStoragePath(), $copy->getStoragePath());

        // Important: save before adding categories
        foreach ($this->categories as $category) {
            $copy->addCategory($category);
        }

        // return
        return $copy;
    }

    /**
     * Load from directory.
     */
    public function loadContent($directory)
    {
        if (is_null($this->uid)) {
            throw new Exception("Cannot locate the storage directory, template does not have a UID");
        }

        // try to find the main file, index.html | index.html | file_name.html | ...
        $indexFile = null;

        // find index
        $possible_indexFile_names = array('index.html', 'index.htm');
        foreach ($possible_indexFile_names as $name) {
            if (is_file($file = join_paths($directory, $name))) {
                $indexFile = $file;
                break;
            }
        }
        // if not find any first html file
        if ($indexFile === null) {
            $objects = scandir($directory);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (!is_dir(join_paths($directory, $object))) {
                        if (preg_match('/\.html?$/i', $object)) {
                            $indexFile = $directory.'/'.$object;
                            break;
                        }
                    }
                }
            }
        }

        // can not find main file
        if ($indexFile === null) {
            $validator = Validator::make(['file' => ''], []);
            $validator->errors()->add('file', 'Cannot find index HTML file');
            throw new ValidationException($validator);
        }

        // read main file content
        $html_content = trim(file_get_contents($indexFile));
        $this->content = $html_content;
        $this->transformAssetsUrls();
        $this->save(); // already save

        // copy template folder
        Tool::xcopy($directory, $this->getStoragePath());

        // return newly created template
        return $this;
    }

    /**
     * Upload a template.
     */
    public static function uploadSystemTemplate($request)
    {
        return self::uploadTemplate($request, true);
    }

    /**
     * Upload a template.
     */
    public static function uploadTemplate($request, $asAdmin = false)
    {
        $user = $request->user();

        $rules = array(
            'file' => 'required|mimetypes:application/zip,application/octet-stream,application/x-zip-compressed,multipart/x-zip',
            'name' => 'required',
        );

        $validator = Validator::make($request->all(), $rules, [
            'file.mimetypes' => 'Input must be a valid .zip file',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // move file to temp place
        $tmpPath = storage_path('tmp/uploaded_template_'.$user->id.'_'.time());
        $tmpName = $request->file('file')->getClientOriginalName();
        $request->file('file')->move($tmpPath, $tmpName);
        $tmpZip = join_paths($tmpPath, $tmpName);

        // read zip file check if zip archive invalid
        $zip = new ZipArchive();
        if ($zip->open($tmpZip, ZipArchive::CREATE) !== true) {
            // @todo hack
            // $validator = Validator::make([], []); // Empty data and rules fields
            $validator->errors()->add('file', 'Cannot open .zip file');
            throw new ValidationException($validator);
        }

        // unzip template archive and remove zip file
        $zip->extractTo($tmpPath);
        $zip->close();
        unlink($tmpZip);

        // Build template's attributes
        $attributes = $request->all();
        $attributes['builder'] = self::BUILDER_DISABLED;

        if ($asAdmin) {
            // Leave customer_id column empty in case of admin
            // The 'admin_id' field is no longer available
            // ### [ deprecated ] $attributes['admin_id'] = $request->user()->admin->id;
        } else {
            $attributes['customer_id'] = $request->user()->customer->id;
        }

        // Save new template
        $template = self::createFromDirectory($attributes, $tmpPath);
        Tool::xdelete($tmpPath);

        return $template;
    }

    public function toZip(): string
    {
        // Get real path for our folder
        $rootPath = $this->getStoragePath();
        $outputPath = join_paths('/tmp/', $this->uid.'.zip');

        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($outputPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();

        return $outputPath;
    }

    /**
     * Get public campaign upload dir.
     */
    public function getStoragePath($path = '/')
    {
        if ($this->customer) {
            // storage/app/users/{uid}/templates
            $base = $this->customer->getTemplatesPath($this->uid);
        } else {
            // storage/app/templates/templates
            // IMPORTANT: templates are created from migration without associating with an admin
            $base = $this->getSystemStoragePath($this->uid);
        }

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    private function getSystemStoragePath($path = null)
    {
        $base = storage_path('app/templates/');

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    /**
     * Get thumb.
     */
    public function getThumbName()
    {
        // find index
        $names = array('thumbnail.svg', 'thumbnail.png', 'thumbnail.jpg', 'thumb.svg', 'thumb.png', 'thumb.jpg');
        foreach ($names as $name) {
            $path = $this->getStoragePath($name);
            if (file_exists($path)) {
                return $name;
            }
        }

        return;
    }

    /**
     * Get thumb.
     */
    public function getThumbUrl()
    {
        if (is_null($this->uid)) {
            throw new Exception('Cannot getThumbUrl(), template does not have a UID, cannot transform content');
        }

        if ($this->getThumbName()) {
            return \Acelle\Helpers\generatePublicPath($this->getStoragePath($this->getThumbName())) . '?' . filemtime($this->getStoragePath($this->getThumbName()));
        } else {
            return url('images/placeholder.jpg');
        }
    }

    public function findCssFiles()
    {
        // IMPORTANT
        // + No external CSS
        // + Only CSS in the template folder is considered
        $files = [];

        $document = new DOMDocument();
        $document->loadHTML($this->content, LIBXML_NOWARNING | LIBXML_NOERROR);
        $links = $document->getElementsByTagName('link');
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if (!empty($href)) {
                $path = $this->getAssetFileFromUrl($href);
                if ($path) {
                    $files[] = $path;
                }
            }
        }
        return $files;
    }

    public function getAssetFileFromUrl($url)
    {
        // To remove query string if any like ?search=abc
        // For example
        //     parse_url('/assets/path/file.jpg?id=320943&search=', PHP_URL_PATH)
        // Returns
        //     /assets/path/file.jpg
        $url = parse_url($url, PHP_URL_PATH);

        // Clean up subdirectory, leaving the url as '/assets/path/file.jpg' only
        $subdirectory = \Acelle\Helpers\getAppSubdirectory();
        if ($subdirectory) {
            // Make sure $subdirectory looks like '/subdir' ==> with a leading slash but without trailing one
            $subdirectory = rtrim(join_paths('/', $subdirectory), '/');

            // Remove subdirectory of URL
            $url = str_replace($subdirectory, '', $url);
        }

        // preg_match('/\/assets\/(?<dirname>[^\/]+)\/(?<basename>[^\/]+)/', '/assets/dir/file.jpg', $match);
        if (strpos($url, '/assets/') === 0) {
            list($prefix, $dirname, $basename) = array_values(array_filter(explode('/', $url)));
            $dirname = StringHelper::base64UrlDecode($dirname);
            $absPath = storage_path(join_paths($dirname, $basename));

            return $absPath;
        } else {
            return null;
        }
    }


    public function extractAssetRelativePath($absPath)
    {
        $myPath = $this->getStoragePath('/');
        if (strpos($absPath, $myPath) !== 0) {
            return null;
        }

        $relativePath = trim(str_replace($myPath, '', $absPath), '/');
        return empty($relativePath) ? null : $relativePath;
    }

    public function getContentWithUntransformedAssetsUrls($untransformUserAssets = false, $processUserAssetCallback = null)
    {
        // Clean up subdirectory, leaving the url as '/assets/path/file.jpg' only
        $subdirectory = \Acelle\Helpers\getAppSubdirectory();
        if ($subdirectory) {
            // Make sure $subdirectory looks like: '/subdir'
            // i.e. with a leading slash but without trailing one
            $subdirectory = rtrim(join_paths('/', $subdirectory), '/');
        }

        // Replace #1
        return StringHelper::transformUrls($this->content, function ($url) use ($subdirectory, $untransformUserAssets, $processUserAssetCallback) {
            if (parse_url($url, PHP_URL_HOST) === false) {
                // if url is invalid
                return $url;
            }

            if (!is_null(parse_url($url, PHP_URL_HOST))) {
                // url is with a host like "http://" or "//"
                return $url;
            }

            if (strpos($url, 'data:') === 0) {
                // base64 image. Like: "data:image/png;base64,iVBOR"
                return $url;
            }

            if (strpos($url, '/') !== 0) {
                // Relative path, ignore
                return $url;
            }

            if ($subdirectory) {
                // Remove subdirectory of URL, keep the leading slash '/'
                $url = str_replace($subdirectory, '', $url);
            }

            // There are two cases when we untransform a URL
            // 1. Template local assets: '/assets/'
            // 2. User library assets: '/files/'

            // preg_match('/\/assets\/(?<dirname>[^\/]+)\/(?<basename>[^\/]+)/', '/assets/dir/file.jpg', $match);
            if (strpos($url, '/assets/') === 0) {
                $assetPath = $this->getAssetFileFromUrl($url);
                $relativePath = $this->extractAssetRelativePath($assetPath);
                if ($relativePath) {
                    return $relativePath;
                } else {
                    return $url;
                }
            } elseif (strpos($url, '/files/') === 0 && $untransformUserAssets) {
                // Okie, now process /files/uid/name.jpg pattern
                list($prefix, $userUid, $basename) = array_values(array_filter(explode('/', $url)));

                $user = User::findByUid($userUid);
                if (is_null($user)) {
                    return $url;
                }

                $assetPath = $user->getAssetsPath($basename);

                // If file no longer exists
                if (!file_exists($assetPath)) {
                    return $url;
                }

                // Callback and return
                if (!is_null($processUserAssetCallback)) {
                    $processUserAssetCallback($assetPath, $basename); // Important: basename may be changed
                }

                // Return to replace
                return $basename;
            } else {
                return $url;
            }
        });
    }

    public function transformAssetsUrls()
    {
        $this->content = $this->getContentWithTransformedAssetsUrls($this->content);
    }

    /**
     * Transform template's relative URLs to application's absolute URL, without hostname.
     * Execute this every time the template is SAVED
     */
    public function getContentWithTransformedAssetsUrls($html, $withHost = false, Closure $urlTransform = null, TrackingDomain $domain = null)
    {
        if (!is_null($domain) && $withHost == false) {
            throw new Exception('Passing $domain parameter while the $withHost parameter is false');
        }

        if (!is_null($urlTransform) && $withHost == false) {
            throw new Exception('Passing $urlTransform parameter while the $withHost parameter is false');
        }

        if (is_null($this->uid)) {
            throw new Exception('Template does not have a UID, cannot transform content');
        }

        // Replace #1
        $content = StringHelper::transformUrls($html, function ($url, $element) use ($withHost, $domain, $urlTransform) {
            if (strpos($url, '#') === 0) {
                return $url;
            }

            if (strpos($url, 'mailto:') === 0) {
                return $url;
            }

            if (parse_url($url, PHP_URL_HOST) === false) {
                // false ==> if url is invalid
                // null ==> if url does not have host information
                return $url;
            }

            if (StringHelper::isTag($url)) {
                return $url;
            }

            if (!is_null(parse_url($url, PHP_URL_HOST))) {
                // url is with a host like "http://" or "//"
                if (!is_null($urlTransform)) {
                    $url = $urlTransform($url, $element);
                }

                if ($domain) {
                    $url = $domain->buildTrackingUrl($url);
                }

                return $url;
            }

            if (strpos($url, '/') === 0) {
                // absolute url with leading slash (/) like "/hello/world"

                $urlWithHost = join_url(getAppHost(), $url);
                if (!is_null($urlTransform)) {
                    $urlWithHost = $urlTransform($urlWithHost, $element);
                }

                if ($domain) {
                    return $domain->buildTrackingUrl($urlWithHost, $element);
                } elseif ($withHost) {
                    return $urlWithHost;
                } else {
                    return $url;
                }
            } elseif (strpos($url, 'data:') === 0) {
                // base64 image. Like: "data:image/png;base64,iVBOR"
                return $url;
            } else {
                // URL is a relative path like "images/banner.jpg"
                // Transform relative URLs to PUBLIC ABSOLUTE URLs with leading slash /
                $url = \Acelle\Helpers\generatePublicPath(
                    $this->getStoragePath($url),
                    $absolute = ($withHost) ? true : false
                );

                if (!is_null($urlTransform)) {
                    $url = $urlTransform($url, $element);
                }

                if ($domain) {
                    return $domain->buildTrackingUrl($url);
                } else {
                    return $url;
                }
            }
        });

        return $content;
    }

    public function wooTransform($body)
    {
        // find all links from contents
        $document = HtmlDomParser::str_get_html($body);

        // Woo Items List
        foreach ($document->find('[builder-element=ProductListElement]') as $element) {
            $max = $element->getAttribute('data-max-items');
            $display = $element->getAttribute('data-display');
            $sort = $element->getAttribute('data-sort-by');

            $request = request();
            $request->merge(['per_page' => $max]);
            $request->merge(['sort_by' => $sort]);

            $items = Product::search($request)->paginate($request->per_page)
                ->map(function ($product, $key) {
                    return [
                        'id' => $product->uid,
                        'name' => $product->title,
                        'price' => $product->price,
                        'image' => $product->getImageUrl(),
                        'description' => substr(strip_tags($product->description), 0, 100),
                        'link' => action('ProductController@index'),
                    ];
                })->toArray();
            $itemsHtml = [];
            foreach ($items as $item) {
                // $element->find('.woo-items')[0]->innertext = 'dddddd';
                $itemsHtml[] = '
                    <div class="woo-col-item mb-4 mt-4 col-md-' . (12/$display) . '">
                        <div class="">
                            <div class="img-col mb-3">
                                <div class="d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <a style="width:100%" href="'.$item["link"].'" class="mr-4"><img width="100%" src="'.($item["image"] ? $item["image"] : url('images/cart_item.svg')).'" style="max-height:200px;max-width:100%;" /></a>
                                </div>
                            </div>
                            <div class="">
                                <p class="font-weight-normal product-name mb-1">
                                    <a style="color: #333;" href="'.$item["link"].'" class="mr-4">'.$item["name"].'</a>
                                </p>
                                <p class=" product-description">'.$item["description"].'</p>
                                <p><strong>'.$item["price"].'</strong></p>
                                <a href="'.$item["link"].'" style="background-color: #9b5c8f;
    border-color: #9b5c8f;" class="btn btn-primary text-white">
                                    ' . trans('messages.automation.view_more') . '
                                </a>
                            </div>
                        </div>
                    </div>
                ';
            }

            $element->find('.products')[0]->innertext = implode('', $itemsHtml);
        }

        // Woo Single Item
        foreach ($document->find('[builder-element=ProductElement]') as $element) {
            $productId = $element->getAttribute('product-id');

            if ($productId) {
                $product = Product::findByUid($productId);

                $item = [
                    'id' => $product->uid,
                    'name' => $product->title,
                    'price' => $product->price,
                    'image' => $product->getImageUrl(),
                    'description' => substr(strip_tags($product->description), 0, 100),
                    'link' => action('ProductController@index'),
                ];
                // $element->find('.product-name', 0)->innertext = $item["name"];
                // $element->find('.product-description', 0)->innertext = $item["description"];
                // $element->find('.product-link', 0)->href = $item["link"];
                // $element->find('.product-price', 0)->innertext = $item["price"];
                $element->find('.product-link img', 0)->src = $item["image"];
                $html = $element->innertext;
                $html = str_replace('*|PRODUCT_NAME|*', $item["name"], $html);
                $html = str_replace('*|PRODUCT_DESCRIPTION|*', $item["description"], $html);
                $html = str_replace('*|PRODUCT_URL|*', $item["link"], $html);
                $html = str_replace('*|PRODUCT_PRICE|*', $item["price"], $html);
                // $html = str_replace('*|PRODUCT_QUANTITY|*', $item["quantity"], $html);
                $element->innertext = $html;
            }
        }

        $body = $document;

        return $body;
    }

    public function uploadAssetFromBase64($base64)
    {
        // upload file by upload image
        $filename = uniqid();

        // Storage path of the uploaded asset:
        // For example: /storage/templates/{type}/{ID}/604ce5e36d0fa
        $filepath = $this->getStoragePath($filename);

        // Store it
        file_put_contents($filepath, file_get_contents($base64));
        $assetUrl = \Acelle\Helpers\generatePublicPath($filepath);

        return $assetUrl;
    }

    public function uploadAssetFromUrl($url)
    {
        // Simply return the image URL
        return $url;

        /* Another way is to fetch and save the image to the local directory
        // upload file by upload image
        $filename = uniqid();

        // Storage path of the uploaded asset:
        // For example: /storage/templates/{type}/{ID}/604ce5e36d0fa
        $filepath = $this->getStoragePath($filename);

        // Download the file's content
        $content = file_get_contents($url);

        // Store it:
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        file_put_contents($filepath, $content, false, stream_context_create($arrContextOptions));
        $assetUrl = \Acelle\Helpers\generatePublicPath($filepath);

        return $assetUrl;
        */
    }

    /**
     * Upload asset.
     */
    public function uploadAsset($file)
    {
        // Store to template storage storage/app/customers/000000/templates/111111/ASSET.JPG
        $name = StringHelper::sanitizeFilename($file->getClientOriginalName());
        $name = StringHelper::generateUniqueName($this->getStoragePath(), $name);

        // Move uploaded file
        $file->move($this->getStoragePath(), $name);
        $assetUrl = \Acelle\Helpers\generatePublicPath($this->getStoragePath($name));

        return $assetUrl;
    }

    /**
     * Template tags.
     *
     * All availabel template tags
     */
    public static function builderTags($list = null)
    {
        $tags = self::tags($list);

        $result = [];

        if (true) {

            // Unsubscribe link
            $result[] = [
                'type' => 'label',
                'text' => '<a href="{UNSUBSCRIBE_URL}">' . trans('messages.editor.unsubscribe_text') . '</a>',
                'tag' => '{UNSUBSCRIBE_LINK}',
                'required' => true,
            ];

            // web view link
            $result[] = [
                'type' => 'label',
                'text' => '<a href="{WEB_VIEW_URL}">' . trans('messages.editor.click_view_web_version') . '</a>',
                'tag' => '{WEB_VIEW_LINK}',
                'required' => true,
            ];
        }

        foreach ($tags as $tag) {
            $result[] = [
                'type' => 'label',
                'text' => '{'.$tag['name'].'}',
                'tag' => '{'.$tag['name'].'}',
                'required' => true,
            ];
        }

        return $result;
    }

    /**
     * Get builder templates.
     *
     * @return mixed
     */
    public function getBuilderAdminTemplates()
    {
        $result = [];

        // Gallery
        $templates = self::shared()->email()
            ->get();

        foreach ($templates as $template) {
            $result[] = [
                'name' => $template->name,
                'url' => action('Admin\TemplateController@builderChangeTemplate', ['uid' => $this->uid, 'change_uid' => $template->uid]),
                'thumbnail' => $template->getThumbUrl(),
            ];
        }

        return $result;
    }

    /**
     * Get builder templates.
     *
     * @return mixed
     */
    public function changeTemplate($template)
    {
        $this->content = $template->content;
        $this->save();

        // delete current template folder
        $this->clearStorage();

        // Copy uploaded folder
        if (file_exists($this->getStoragePath())) {
            if (!file_exists($this->getStoragePath())) {
                mkdir($this->getStoragePath(), 0777, true);
            }

            Tool::xcopy($template->getStoragePath(), $this->getStoragePath());
        }
    }

    /**
     * Upload template thumbnail.
     *
     * @return mixed
     */
    public function uploadThumbnail($file)
    {
        $file->move($this->getStoragePath(), 'thumbnail.png');
        // resize
        resize_crop_image(596, 769, $this->getStoragePath('thumbnail.png'), $this->getStoragePath('thumbnail.png'));
    }

    /**
     * Upload template thumbnail Url.
     *
     * @return mixed
     */
    public function uploadThumbnailUrl($url)
    {
        $contents = file_get_contents($url);
        file_put_contents($this->getStoragePath('thumbnail.png'), $contents);
        // resize
        resize_crop_image(596, 769, $this->getStoragePath('thumbnail.png'), $this->getStoragePath('thumbnail.png'));
    }

    /**
     * Create template from dir.
     *
     * @return Template
     */
    public static function createFromDirectory($attributes, $directory)
    {
        $template = new self();
        $template->fill($attributes); // Including 'uid', 'name', 'content', 'builder'

        // System or Customer template
        if (array_key_exists('customer_id', $attributes)) {
            $template->customer_id = $attributes['customer_id'];
        }

        // UID is needed for loading the conent
        if (is_null($template->uid)) {
            $template->uid = uniqid();
        }

        $template->loadContent($directory); // already saved!
        return $template;
    }

    public function clearStorage()
    {
        Tool::xdelete($this->getStoragePath());
    }

    public function deleteAndCleanup()
    {
        $this->clearStorage();
        $this->delete();
    }

    public static function resetDefaultTemplates()
    {
        // DELTEE categories
        TemplateCategory::query()->delete();
        foreach (self::default() as $template) {
            $template->deleteAndCleanup();
        }

        // CREATE Cateogries again
        $categoryBasic = TemplateCategory::create(['name' => 'Basic']);
        $categoryFeatured = TemplateCategory::create(['name' => 'Featured']);
        $categoryTheme = TemplateCategory::create(['name' => 'Themes']);
        $categoryWoo = TemplateCategory::create(['name' => 'WooCommerce']);

        // LIST all default templates here
        $templates = [
            [
                'name' => 'Blank',
                'dir' => database_path('templates/basic/000-blank/6037a0a8583a7'),
                'category' => $categoryBasic,
            ], [
                'name' => 'Pricing Table',
                'dir' => database_path('templates/basic/001-pricing-table/6037a2135b974'),
                'category' => $categoryBasic,
            ], [
                'name' => 'Lists & Tables',
                'dir' => database_path('templates/basic/002-lists-tables/6037a2250a3a3'),
                'category' => $categoryBasic,
                'builder' => true,
            ], [
                'name' => 'One column layout',
                'dir' => database_path('templates/basic/003-1-column/6037a28418c95'),
                'category' => $categoryBasic,
                'builder' => true,
            ], [
                'name' => '1-2 column layout',
                'dir' => database_path('templates/basic/004-1-2-columns/6037a24ebdbd6'),
                'category' => $categoryBasic,
                'builder' => true,
            ], [
                'name' => '1-2-1 column layout',
                'dir' => database_path('templates/basic/005-1-2-1-columns/6037a2401b055'),
                'category' => $categoryBasic,
                'builder' => true,
            ], [
                'name' => '1-3 column layout',
                'dir' => database_path('templates/basic/006-1-3-columns/6037a275bf375'),
                'category' => $categoryBasic,
                'builder' => true,
            ], [
                'name' => '1-3-1 column layout',
                'dir' => database_path('templates/basic/007-1-3-1-columns/6037a25ddce80'),
                'category' => $categoryBasic,
                'builder' => true,
            ], [
                'name' => '1-3-2 column layout',
                'dir' => database_path('templates/basic/008-1-3-2-columns/6037a26b0a286'),
                'category' => $categoryBasic,
                'builder' => true,
            ], [
                'name' => 'Two columns layout',
                'dir' => database_path('templates/basic/009-2-columns/6037a2b67ed27'),
                'category' => $categoryBasic,
                'builder' => true,
            ], [
                'name' => '2-1 column layout',
                'dir' => database_path('templates/basic/010-2-1-columns/6037a2aa315d4'),
                'category' => $categoryBasic,
                'builder' => true,
            ], [
                'name' => '2-1-2 column layout',
                'dir' => database_path('templates/basic/011-2-1-2-columns/6037a29a35e05'),
                'category' => $categoryBasic,
                'builder' => true,
            ], [
                'name' => 'Three columns layout',
                'dir' => database_path('templates/basic/012-3-columns/6037a2dcb6c56'),
                'category' => $categoryBasic,
                'builder' => true,
            ], [
                'name' => '3-1-3 column layout',
                'dir' => database_path('templates/basic/013-3-1-3-columns/6037a2c3d7fa1'),
                'category' => $categoryBasic,
                'builder' => true,
            ], [
                'name' => 'Abandoned Cart Email #2',
                'dir' => database_path('templates/woos/001-woo-2/6037a26b0a200'),
                'category' => $categoryWoo,
                'builder' => true,
            ], [
                'name' => 'Abandoned Cart Email #3',
                'dir' => database_path('templates/woos/002-woo-3/6037a26b0a211'),
                'category' => $categoryWoo,
                'builder' => true,
            ], [
                'name' => 'Abandoned Cart Email #4',
                'dir' => database_path('templates/woos/003-woo-4/6037a26b0a244'),
                'category' => $categoryWoo,
                'builder' => true,
            ], [
                'name' => 'Certified Yoga Therapist',
                'dir' => database_path('templates/featured/001-yoga/52264e8382883'),
                'category' => $categoryFeatured,
                'builder' => true,
                'theme' => 'yoga',
            ], [
                'name' => 'The hunt is on!',
                'dir' => database_path('templates/featured/002-sport/00464e8382883'),
                'category' => $categoryFeatured,
                'builder' => true,
            ], [
                'name' => 'Give a gift. Change a life',
                'dir' => database_path('templates/featured/003-give-away/01464e8382883'),
                'category' => $categoryFeatured,
                'builder' => true,
                'theme' => 'kids',
            ], [
                'name' => 'Gift Card!',
                'dir' => database_path('templates/featured/004-yellow/5d7b527be2bd2'),
                'category' => $categoryFeatured,
                'builder' => true,
            ], [
                'name' => 'Color Print - Your Print Companion',
                'dir' => database_path('templates/featured/005-blue/5d7b526bbfd4c'),
                'category' => $categoryFeatured,
                'builder' => true,
            ], [
                'name' => 'Color Print - Your Print Companion',
                'dir' => database_path('templates/featured/005-blue/5d7b526bbfd4c'),
                'category' => $categoryFeatured,
                'builder' => true,
                'name' => 'News Digest',
                'dir' => database_path('templates/featured/000-04-rss-feed/6037a2356820zs'),
                'category' => $categoryFeatured,
                'builder' => true,
            ],
        ];

        // Delete existing template and create again with new updates
        foreach ($templates as $meta) {
            if (!File::exists($meta['dir'])) {
                continue;
            }

            // UID is the folder name, "5d7b527be2bd2" for example
            $uid = basename($meta['dir']);

            // DELETE existing template, in case this migration is executed many times
            Template::where('uid', $uid)->delete();

            // Build $template attributes and create
            $meta['uid'] = $uid;
            $meta['is_default'] = true; // default templates that are originally created
            $meta['builder'] = true;
            $meta['type'] = Template::TYPE_EMAIL;
            $template = Template::createFromDirectory($meta, $meta['dir']);

            // Set category
            $template->categories()->attach($meta['category']->id);
        }
    }

    public function createTmpZip()
    {
        $tmpDir = storage_path("tmp/template-" . $this->uid);
        $tmpZipFile = "{$tmpDir}.zip";
        $indexFile = join_paths($tmpDir, 'index.html');

        // Copy template folder to tmp place
        \Acelle\Helpers\pcopy($this->getStoragePath(), $tmpDir);

        // Transform templates URLs like src='/assets/base64/file.jpg' to src='file.jpg'
        $html = $this->getContentWithUntransformedAssetsUrls(
            $alsoUntransformUserAssets = true,
            function ($userAssetFile, &$basename) use ($tmpDir) {
                $basename = StringHelper::generateUniqueName($tmpDir, $basename);
                $copyPath = join_paths($tmpDir, $basename);
                \Acelle\Helpers\pcopy($userAssetFile, $copyPath);
            }
        );

        // Create index.html
        file_put_contents($indexFile, $html);

        // Make zip file
        Tool::zip($tmpDir, $tmpZipFile);

        // Clean up tmp directory
        File::deleteDirectory($tmpDir);

        // Return zip file for download
        return $tmpZipFile;
    }

    public function changeName($name)
    {
        $validator = \Validator::make(['name' => $name], [
            'name' => 'required',
        ]);

        // redirect if fails
        if ($validator->fails()) {
            return $validator;
        }

        $this->name = $name;
        $this->save();

        return $validator;
    }

    public function updateContent($content)
    {
        $this->content = $content;
        $this->save();
    }

    public static function resetPopupTemplates()
    {
        TemplateCategory::where('name', '=', 'Form')->delete();

        // LIST all form templates here
        $templates = [
            [
                'name' => 'Right banner',
                'dir' => database_path('templates/forms/009-layout-right/6002a2135b900'),
            ], [
                'name' => 'Left banner',
                'dir' => database_path('templates/forms/011-layout-left/6002a2135b901'),
            ], [
                'name' => 'Top banner',
                'dir' => database_path('templates/forms/010-layout-top/6002a2135b902'),
            ], [
                'name' => 'Bottom banner',
                'dir' => database_path('templates/forms/012-layout-bottom/6002a2135b903'),
            ], [
                'name' => 'Bottom banner',
                'dir' => database_path('templates/forms/013-layout-clean/6002a2135b904'),
            ], [
                'name' => 'Dark Simple',
                'dir' => database_path('templates/forms/001-dark/60cca2135b900'),
            // ], [
            //     'name' => 'Pricing Table',
            //     'dir' => database_path('templates/forms/002-2-columns/6002a2135b974'),
            //     'category' => $categoryWoo,
            ], [
                'name' => 'Sign Up Illustration',
                'dir' => database_path('templates/forms/003-illustration/6003a2250a3a3'),
            ], [
                'name' => 'Profile Center',
                'dir' => database_path('templates/forms/004-center/6004a28418c95'),
            // ], [
            //     'name' => '1-2 column layout',
            //     'dir' => database_path('templates/forms/005-full-background/6005a24ebdbd6'),
            //     'category' => $categoryWoo,
            // ], [
            //     'name' => '1-2-1 column layout',
            //     'dir' => database_path('templates/forms/006-grap-money/6006a2401b055'),
            //     'category' => $categoryWoo,
            // ], [
            //     'name' => '1-3 column layout',
            //     'dir' => database_path('templates/forms/007-checkout/6007a275bf375'),
            //     'category' => $categoryWoo,
            // ], [
            //     'name' => '1-3-1 column layout',
            //     'dir' => database_path('templates/forms/008-blue-visa/6008a25ddce80'),
            //     'category' => $categoryWoo,
            ],
        ];

        // Delete existing template and create again with new updates
        foreach ($templates as $meta) {
            // UID is the folder name, "5d7b527be2bd2" for example
            $uid = basename($meta['dir']);

            // DELETE existing template, in case this migration is executed many times
            Template::where('uid', $uid)->delete();

            // Build $template attributes and create
            $meta['uid'] = $uid;
            $meta['is_default'] = true; // default templates that are originally created
            $meta['builder'] = true;
            $meta['type'] = Template::TYPE_POPUP;
            $template = Template::createFromDirectory($meta, $meta['dir']);
        }
    }

    public function urlTagsDropdown()
    {
        return [
            ['value' => '{UNSUBSCRIBE_URL}', 'text' => trans('messages.editor.unsubscribe_text')],
            ['value' => '{UPDATE_PROFILE_URL}', 'text' => trans('messages.editor.update_profile_text')],
            ['value' => '{WEB_VIEW_URL}', 'text' => trans('messages.editor.click_view_web_version')],
        ];
    }

    public function getPreviewContent()
    {
        // Bind subscriber/message/server information to email content
        $pipeline = new PipelineBuilder();
        $pipeline->add(new TransformWidgets());

        // Actually push HTML to pipeline for processing
        $html = $pipeline->build()->process($this->content);

        // Return subscriber's bound html
        return $html;
    }

    public static function defaultRssConfig()
    {
        return [
            'url' => '',
            'size' => 10,
            'templates' => [
                'FeedTitle' => [
                    'title' => trans('messages.rss.feed_title'),
                    'show' => true,
                    'template' => '@feed_title',
                ],
                'FeedSubtitle' => [
                    'title' => trans('messages.rss.feed_subtitle'),
                    'show' => true,
                    'template' => 'Updated at: @feed_build_date',
                ],
                'FeedTagdLine' => [
                    'title' => trans('messages.rss.feed_tagline'),
                    'show' => true,
                    'template' => trans('messages.rss.top_stories_for_you'),
                ],
                'ItemTitle' => [
                    'title' => trans('messages.rss.item_title'),
                    'show' => true,
                    'template' => 'Title: @item_title',
                ],
                'ItemMeta' => [
                    'title' => trans('messages.rss.meta_line'),
                    'show' => true,
                    'template' => '<img src="'.url('images/avatar1.svg').'" width="30px" style="margin-right:5px" /> something here - @item_pubdate',
                ],
                'ItemDescription' => [
                    'title' => trans('messages.rss.item_description'),
                    'show' => true,
                    'template' => '@item_description <a href="@item_url">Read more</a>',
                ],
                'ItemStats' => [
                    'title' => trans('messages.rss.stats_line'),
                    'show' => true,
                    'template' => '<img src="'.url('images/icon-up.svg').'" width="16px" style="margin-right:5px" /> 400k updates, &nbsp; &nbsp; 
                        <img src="'.url('images/icon-comment.svg').'" width="16px" style="margin-right:5px" /> 1.2k comments',
                ],
                'ItemEnclosure' => [
                    'title' => trans('messages.rss.enclosure'),
                    'show' => false,
                    'template' => '@item_enclosure',
                ],
            ],
        ];
    }
}
