<?php

use function Acelle\Helpers\xml_to_array;

/**
 * Globally available helper methods.
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

/**
 * Get full table name by adding the DB prefix.
 *
 * @param string table name
 *
 * @return string fulle table name with prefix
 */
function table($name)
{
    return \DB::getTablePrefix().$name;
}

/**
 * Quote a value with astrophe to inject to an SQL statement.
 *
 * @param string original value
 *
 * @return string quoted value
 * @todo: use MySQL escape function to correctly escape string with astrophe
 */
function quote($value)
{
    return "'$value'";
}

/**
 * Quote a value with astrophe to inject to an SQL statement.
 *
 * @param string original value
 *
 * @return string quoted value
 * @todo: use MySQL escape function to correctly escape string with astrophe
 */
function db_quote($value)
{
    return \DB::connection()->getPdo()->quote($value);
}

/**
 * Break an array into smaller batches (arrays).
 *
 * @param array original array
 * @param int batch size
 * @param bool whether or not to skip the first header line
 * @param callback function
 */
function each_batch($array, $batchSize, $skipHeader, $callback)
{
    $batch = [];
    foreach ($array as $i => $value) {
        // skip the header
        if ($i == 0 && $skipHeader) {
            continue;
        }

        if ($i % $batchSize == 0) {
            $callback($batch);
            $batch = [];
        }
        $batch[] = $value;
    }

    // the last callback
    if (sizeof($batch) > 0) {
        $callback($batch);
    }
}

/**
 * Join filesystem path strings.
 *
 * @param * parts of the path
 *
 * @return string a full path
 */
function join_paths()
{
    $paths = array();
    foreach (func_get_args() as $arg) {
        if (preg_match('/http:\/\//i', $arg)) {
            throw new \Exception('Path contains http://! Use `join_url` instead. Error for '.implode('/', func_get_args()));
        }

        if ($arg !== '') {
            $paths[] = $arg;
        }
    }

    return preg_replace('#/+#', '/', implode('/', $paths));
}

/**
 * Join URL parts.
 *
 * @param * parts of the URL. Note that the first part should be something like http:// or http://host.name
 *
 * @return string a full URL
 */
function join_url()
{
    $paths = array();
    foreach (func_get_args() as $arg) {
        if (!empty($arg)) {
            $paths[] = $arg;
        }
    }

    return preg_replace('#(?<=[^:])/+#', '/', implode('/', $paths));
}

/**
 * Get unique array based on user defined condition.
 *
 * @param array original array
 *
 * @return array unique array
 */
function array_unique_by($array, $callback)
{
    $result = [];
    foreach ($array as $value) {
        $key = $callback($value);
        $result[$key] = $value;
    }

    return array_values($result);
}

function get_localization_config($name, $locale)
{
    $defaultConfig = config('localization')['*'];

    if (array_key_exists($locale, config('localization'))) {
        $config = config('localization')[$locale];
    }

    if (isset($config) && array_key_exists($name, $config) && array_key_exists($name, $defaultConfig)) {
        return $config[$name];
    } elseif (array_key_exists($name, $defaultConfig)) {
        return $defaultConfig[$name];
    } else {
        throw new \Exception('Localization config for "'.$name.'" does not exist');
    }
}

function get_datetime_format($name, $locale)
{
    $defaultConfig = config('localization')['*'];

    if (array_key_exists($locale, config('localization'))) {
        $config = config('localization')[$locale];
    }

    if (isset($config) && array_key_exists($name, $config) && array_key_exists($name, $defaultConfig)) {
        return $config[$name];
    } elseif (array_key_exists($name, $defaultConfig)) {
        return $defaultConfig[$name];
    } else {
        throw new \Exception('AC: Invalid datetime format type: '.$name.' => make sure the type is available in BOTH local and default (*) settings');
    }
}

function format_datetime(?\Carbon\Carbon $datetime, $name, $locale)
{
    if (is_null($datetime)) {
        return;
    }
    return $datetime->format(get_datetime_format($name, $locale));
}

/**
 * Get UTC offset of a particular time zone.
 *
 * @param string timezone
 *
 * @return string UTC offset (+02:00 for example)
 */
function utc_offset($timezone)
{
    $offset = \Carbon\Carbon::now($timezone)->offsetHours - \Carbon\Carbon::now('UTC')->offsetHours;

    return sprintf("%+'03d:00", $offset);
}

/**
 * Check if exec() function is available.
 *
 * @return bool
 */
function exec_enabled()
{
    try {
        // make a small test
        exec('ls');

        return function_exists('exec') && !in_array('exec', array_map('trim', explode(', ', ini_get('disable_functions'))));
    } catch (\Throwable $ex) {
        return false;
    }
}

/**
 * Run artisan migrate.
 *
 * @return bool
 */
function artisan_migrate()
{
    \Artisan::call('migrate', ['--force' => true]);
}

/**
 * Check if site is in demo mod.
 *
 * @return bool
 */
function isSiteDemo()
{
    return config('app.demo');
}

/**
 * Get language code.
 *
 * @return string
 */
function language_code()
{
    // Get default language code from setting
    $default_language = \Acelle\Model\Language::find(\Acelle\Model\Setting::get('default_language'));

    if (isset($_COOKIE['last_language_code'])) {
        $language_code = $_COOKIE['last_language_code'];
    } elseif (app()->getLocale()) {
        $language_code = app()->getLocale();
    } elseif (is_object($default_language)) {
        $language_code = $default_language->code;
    } else {
        $language_code = 'en';
    }

    return $language_code;
}

/**
 * Get language code.
 *
 * @return string
 */
function language()
{
    return \Acelle\Model\Language::where('code', '=', language_code())->first();
}

/**
 * Format a number as percentage.
 *
 * @return string
 */
function number_to_percentage($number, $precision = 2)
{
    if (!is_numeric($number)) {
        return $number;
    }

    return sprintf("%.{$precision}f%%", $number * 100);
}

/**
 * Format a number with delimiter.
 *
 * @return string
 */
function number_with_delimiter($number, $precision = null, $seperator = null, $locale = null)
{
    if (!is_numeric($number)) {
        return $number;
    }

    if (is_null($locale)) {
        $locale = config('app.locale');
    }

    // Trick!
    if (floor($number) == $number && is_null($precision)) {
        $precision = 0;
    }

    if (is_null($precision)) {
        $precision = get_localization_config('number_precision', $locale);
    }

    $decimal = get_localization_config('number_decimal_separator', $locale);

    if (is_null($seperator)) {
        $seperator = get_localization_config('number_thousands_separator', $locale);
    }

    return number_format($number, $precision, $decimal, $seperator);
}

/**
 * Overwrite the Laravel's Builder#paginate, accept a $total parameter specifying the total number of records.
 *
 * @param int      $perPage
 * @param array    $columns
 * @param string   $pageName
 * @param int|null $page
 *
 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
 */
function optimized_paginate($builder, $perPage = 15, $columns = null, $pageName = null, $page = null, $total = null)
{
    $pageName = $pageName ?: 'page';
    $page = $page ?: \Illuminate\Pagination\Paginator::resolveCurrentPage($pageName);
    $columns = $columns ?: ['*'];
    $total = is_null($total) ? $builder->getCountForPagination() : $total;
    // in case $total == 0
    $results = $total ? $builder->forPage($page, $perPage)->get($columns) : collect([]);

    return new \Illuminate\Pagination\LengthAwarePaginator($results, $total, $perPage, $page, [
    'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
    'pageName' => $pageName,
  ]);
}

/**
 * Distinct count helper for performance.
 *
 * @return int
 */
function distinctCount($builder, $column = null, $method = 'group')
{
    $q = clone $builder;
    /*
     * There are 2 options to COUNT DISTINCT
     *   1. Use DISTINCT
     *   2. Use GROUP BY
     * Normally GROUP BY yields better performance (for example: 500,000 records, DISTINCT -> 7 seconds, GROUP BY -> 1.9 seconds)
     **/

    if (is_null($column)) {
        // just count it
    } elseif ($method == 'group') {
        $q->groupBy($column)->select($column);
    } elseif ($method == 'distinct') {
        $q->select($column)->distinct();
    }

    // Result
    $count = \DB::table(\DB::raw("({$q->toSql()}) as sub"))
        ->addBinding($q->getBindings()) // you need to get underlying Query Builder
        ->count();

    return $count;
}

/**
 * Check if function is enabled.
 *
 * @return bool
 */
function func_enabled($name)
{
    try {
        $disabled = explode(',', ini_get('disable_functions'));

        return !in_array($name, $disabled);
    } catch (\Exception $ex) {
        return false;
    }
}

/**
 * Get the current application version.
 *
 * @return string version
 */
function app_version()
{
    return trim(file_get_contents(base_path('VERSION')));
}

/**
 * Extract email from a string
 * For example: get abc@mail.com from "My Name <abc@mail.com>".
 *
 * @return string version
 */
function extract_email($str)
{
    preg_match("/(?<email>[-0-9a-zA-Z\.+_]+@[-0-9a-zA-Z\.+_]+\.[a-zA-Z]+)/", $str, $matched);
    if (array_key_exists('email', $matched)) {
        return $matched['email'];
    } else {
        return;
    }
}

/**
 * Extract name from a string
 * For example: get abc@mail.com from "My Name <abc@mail.com>".
 *
 * @return string version
 */
function extract_name($str)
{
    $parts = explode('<', $str);
    if (count($parts) > 1) {
        return trim($parts[0]);
    }
    $parts = explode('@', extract_email($str));

    return $parts[0];
}

/**
 * Extract domain from an email
 * For example: get mail.com from "My Name <abc@mail.com>".
 *
 * @return string version
 */
function extract_domain($email)
{
    $email = extract_email($email);
    $domain = substr(strrchr($email, '@'), 1);

    return $domain;
}

/**
 * Doublequote a string.
 *
 * @return string
 */
function doublequote($str)
{
    return sprintf('"%s"', preg_replace('/^"+|"+$/', '', $str));
}

/**
 * Format price.
 *
 * @param string
 *
 * @return string
 */
function format_price($price, $format = '{PRICE}', $html=false)
{
    if ($html) {
        $html = str_replace('{PRICE}', ' <span class="p-amount">' . number_with_delimiter($price) . '</span> ', $format);
        // $html = str_replace(number_with_delimiter($price), ' <span class="p-amount">' . number_with_delimiter($price) . '</span> ', $html);
        return $html;
    } else {
        return str_replace('{PRICE}', number_with_delimiter($price), $format);
    }
}

/**
 * Check if the app is initiated.
 *
 * @return bool
 */
function isInitiated()
{
    return file_exists(storage_path('app/installed'));
}

function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2).' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2).' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2).' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes.' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes.' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

/**
 * Get random item from array.
 *
 * @return object
 */
function rand_item($arr)
{
    return $arr[array_rand($arr)];
}

/**
 * Check if string is email.
 *
 * @return object
 */
function checkEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function demo_auth()
{
    $auth = \Acelle\Model\User::getAuthenticateFromFile();

    return [
        'email' => isset($auth['email']) ? $auth['email'] : '',
        'password' => $auth['password'] ? $auth['password'] : '',
    ];
}

function get_app_identity()
{
    return md5(config('app.key'));
}

function quoteDotEnvValue($value)
{
    $containsSharp = (strpos($value, '#') !== false);

    if ($containsSharp) {
        $value = str_replace('"', '\"', $value);
        $value = '"'.$value.'"';
    }

    return $value;
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Strip Tags Only
 *
 * Just like strip_tags, but only removes the HTML tags specified and not all of
 * them.
 *
 * @param String $text The text to strip the tags from.
 * @param String|Array $allowedTags This can either be one tag (eg. 'p') or an
 *     array, (eg. ['p','br','h1']).
 * @return String The text with the mentioned tags stripped.
 * @author Aalaap Ghag <aalaap@gmail.com>
 */
function strip_tags_only($text, $allowedTags = [])
{
    if (!is_array($allowedTags)) {
        $allowedTags = [
            $allowedTags
        ];
    }

    array_map(
        function ($allowedTag) use (&$text) {
            $regEx = '#<' . $allowedTag . '.*?>(.*?)</' . $allowedTag . '>#is';
            $text = preg_replace($regEx, '', $text);
        },
        $allowedTags
    );

    return $text;
}

/**
 * Get controller action name
 **/
function controllerAction()
{
    // GET FROM SCREEN OPTION
    $controller = explode('\\', request()->route()->getAction()['controller']);
    return $controller[count($controller)-1];
}

/**
 * Get controller name
 **/
function controllerName()
{
    $controllerAction = controllerAction();
    return explode('@', $controllerAction)[0];
}

/**
 * Get action name
 **/
function actionName()
{
    $controllerAction = controllerAction();
    return explode('@', $controllerAction)[1];
}

/*
 *  Iterate through a Eloquent $query using cursor paginate
 *  The $orderBy parameter is critically required for a cursor pagination
 */
function cursorIterate($query, $orderBy, $size, $callback)
{
    $cursor = null;
    $page = 1;
    do {
        $q = clone $query;
        // The 4th parameter contains the offset cursor
        $list = $q->orderBy($orderBy)->cursorPaginate($size, ['*'], 'cursor', $cursor);
        $callback($list->items(), $page);
        $cursor = $list->nextCursor();
        $page += 1;
    } while ($list->hasMorePages());
}

/**
 * Convert html to inline.
 *
 * @todo not very OOP here, consider moving this to a Helper instead
*/
function makeInlineCss($html, array $cssFiles)
{
    // disable warning when parsing html content
    libxml_use_internal_errors(true);

    $htmldoc = new \Acelle\Library\InlineStyleWrapper($html);

    foreach ($cssFiles as $file) {
        // For safety, in case template is uploaded by user
        if (file_exists($file)) {
            $styles = file_get_contents($file);
            $htmldoc->applyStylesheet($styles);
        }
    }

    return $htmldoc->getHTML();
}

//resize and crop image by center
function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80)
{
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    $mime = $imgsize['mime'];

    switch ($mime) {
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
            break;

        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $quality = 7;
            break;

        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $quality = 80;
            break;

        default:
            return false;
            break;
    }

    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = $image_create($source_file);

    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
    if ($width_new > $width) {
        //cut point by height
        $h_point = (($height - $height_new) / 2);
        //copy image
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    } else {
        //cut point by width
        $w_point = (($width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }

    $image($dst_img, $dst_dir, $quality);

    if ($dst_img) {
        imagedestroy($dst_img);
    }
    if ($src_img) {
        imagedestroy($src_img);
    }
}

function filterSearchArray($items, $keyword)
{
    // search
    $results = [];
    foreach ($items as $item) {
        $row = [
            'rate' => 0,
            'item' => $item,
        ];

        if (isset($keyword)) {
            $keyword = trim(strtolower($keyword));

            // Keywords
            if (!empty($keyword)) {
                $keywords = preg_split('/\s+/', $keyword);
                $allExist = true;

                foreach ($keywords as $keyword) {
                    $exist = false;

                    // search by names
                    foreach ($item['names'] as $name) {
                        $name = trim(strtolower($name));
                        if (strpos($name, $keyword) !== false) {
                            $row['rate'] += 1;
                            $exist = true;
                        }
                    }

                    // search by keywords
                    if (isset($item['keywords'])) {
                        foreach ($item['keywords'] as $k) {
                            $k = trim(strtolower($k));
                            if (strpos($k, $keyword) !== false) {
                                $row['rate'] += 1;
                                $exist = true;
                            }
                        }
                    }

                    if (!$exist) {
                        $allExist = false;
                    }
                }

                if (!$allExist) {
                    $row['rate'] = 0;
                }
            }
        }

        if ($row['rate'] > 0) {
            $results[] = $row;
        }
    }

    // sort
    usort($results, function ($a, $b) {
        if ($a['rate'] != $b['rate']) {
            return $a['rate'] <=> $b['rate'];
        } else {
            return strcmp(implode(' ', $a['item']['names']), implode(' ', $b['item']['names']));
        }
    });

    return $results;
}

function getPeriodEndsAt($startDate, $amount, $unit)
{
    switch ($unit) {
        case 'month':
            $endsAt = $startDate->addMonthsNoOverflow($amount);
            break;
        case 'day':
            $endsAt = $startDate->addDay($amount);
            break;
        case 'week':
            $endsAt = $startDate->addWeek($amount);
            break;
        case 'year':
            $endsAt = $startDate->addYearsNoOverflow($amount);
            break;
        default:
            throw new \Exception('Invalid time period unit: ' . $unit);
    }

    return $endsAt;
}

function getThemeColor($theme = false)
{
    $colors = [
        'default' => 'rgba(13, 24, 29, 0.85)',
        'blue' => 'rgba(9, 22, 28, 0.9)',
        'green' => 'rgba(11, 29, 29, 0.9)',
        'brown' => 'rgba(27, 21, 10, 0.9)',
        'pink' => 'rgba(28, 11, 19, 0.9)',
        'grey' => '#111111',
        'white' => '#444',
    ];

    if (!$theme || !isset($colors[$theme])) {
        return $colors['default'];
    }

    return $colors[$theme];
}

function getThemeMode($mode, $auto='light')
{
    $themeMode = $mode;

    if ($mode == 'auto') {
        if ($auto) {
            $themeMode = $auto;
        }
    }

    return $themeMode;
}

function parseRss($config)
{
    $rss = [];

    // Parse RSS content
    $rssArray = xml_to_array(simplexml_load_string(file_get_contents($config['url']), 'SimpleXMLElement', LIBXML_NOCDATA));
    $rssFeed = simplexml_load_string(file_get_contents($config['url']), 'SimpleXMLElement', LIBXML_NOCDATA);

    // Take 10 records only
    $records = array_slice($rssArray['rss']['channel']['item'], 0, $config['size']);

    // feed data
    $feedData = [];
    $feedData['feed_title'] = (string) $rssFeed->channel->title;
    $feedData['feed_description'] = $rssFeed->channel->description->__toString();
    $feedData['feed_link'] = $rssFeed->channel->link->__toString();
    $feedData['feed_pubdate'] = $rssFeed->channel->pubDate->__toString();
    $feedData['feed_build_date'] = $rssFeed->channel->lastBuildDate->__toString();

    // feed parse template
    $rss['FeedTitle'] = parseRssTemplate($config['templates']['FeedTitle']['template'], $feedData);
    $rss['FeedSubtitle'] = parseRssTemplate($config['templates']['FeedSubtitle']['template'], $feedData);
    $rss['FeedTagdLine'] = parseRssTemplate($config['templates']['FeedTagdLine']['template'], $feedData);

    // records
    $rss['items'] = [];
    $count = 0;
    foreach ($rssFeed->channel->item as $item) {
        // item data
        $itemData['item_title'] = $item->title;
        $itemData['item_pubdate'] = $item->pubDate;
        $itemData['item_description'] = $item->description;
        $itemData['item_url'] = $item->link;
        $itemData['item_enclosure_url'] = $item->enclosure['url'];
        $itemData['item_enclosure_type'] = $item->enclosure['type'];

        // item parse template
        $item = [];
        $item['ItemTitle'] = parseRssTemplate($config['templates']['ItemTitle']['template'], $itemData);
        $item['ItemDescription'] = parseRssTemplate($config['templates']['ItemDescription']['template'], $itemData);
        $item['ItemMeta'] = parseRssTemplate($config['templates']['ItemMeta']['template'], $itemData);
        $item['ItemEnclosure'] = parseRssTemplate($config['templates']['ItemEnclosure']['template'], $itemData);
        $item['ItemStats'] = parseRssTemplate($config['templates']['ItemStats']['template'], $itemData);

        // add item to rss items
        $rss['items'][] = $item;

        $count += 1;
        if ($config['size'] == $count) {
            break;
        }
    }


    // Return HTML
    return view('helpers.rss.template', [
        'rss' => $rss,
        'templates' => $config['templates'],
    ]);
}

function parseRssTemplate($template, $feedData)
{
    foreach ($feedData as $key => $value) {
        $template = str_replace('@' . $key, $value, $template);
    }

    if (isset($feedData['item_enclosure_url']) && $feedData['item_enclosure_url'] != '') {
        if (strpos($feedData['item_enclosure_type'], 'video') !== false) {
            $html = '<video controls width="320">
                        <source src="https://file-examples-com.github.io/uploads/2017/04/file_example_MP4_480_1_5MG.mp4" type="audio/mpeg">
                        Your browser does not support the audio element.
                </video>';
        } elseif (strpos($feedData['item_enclosure_type'], 'video') !== false) {
            $html = '<audio controls>
                        <source src="' . $feedData['item_enclosure_url'] . '" type="audio/mpeg">
                        Your browser does not support the audio element.
                </audio>';
        } else {
            $html = '<img class="my-2" src="' . $feedData['item_enclosure_url'] . '" height="100px" />';
        }
        $template = str_replace('@item_enclosure', $html, $template);
    }

    return $template;
}

function rssTags()
{
    return [
        'feed' => [
            '@feed_title',
            '@feed_description',
            '@feed_link',
            '@feed_pubdate',
            '@feed_build_date',
        ],
        'item' => [
            '@item_title',
            '@item_pubdate',
            '@item_description',
            '@item_image_url',
        ],
    ];
}

function getFullCodeByLanguageCode($languageCode)
{
    $locales = array(
        'af-ZA',
        'am-ET',
        'ar-AE',
        'ar-BH',
        'ar-DZ',
        'ar-EG',
        'ar-IQ',
        'ar-JO',
        'ar-KW',
        'ar-LB',
        'ar-LY',
        'ar-MA',
        'arn-CL',
        'ar-OM',
        'ar-QA',
        'ar-SA',
        'ar-SY',
        'ar-TN',
        'ar-YE',
        'as-IN',
        'az-Cyrl-AZ',
        'az-Latn-AZ',
        'ba-RU',
        'be-BY',
        'bg-BG',
        'bn-BD',
        'bn-IN',
        'bo-CN',
        'br-FR',
        'bs-Cyrl-BA',
        'bs-Latn-BA',
        'ca-ES',
        'co-FR',
        'cs-CZ',
        'cy-GB',
        'da-DK',
        'de-AT',
        'de-CH',
        'de-DE',
        'de-LI',
        'de-LU',
        'dsb-DE',
        'dv-MV',
        'el-GR',
        'en-US',
        'en-029',
        'en-AU',
        'en-BZ',
        'en-CA',
        'en-GB',
        'en-IE',
        'en-IN',
        'en-JM',
        'en-MY',
        'en-NZ',
        'en-PH',
        'en-SG',
        'en-TT',
        'en-ZA',
        'en-ZW',
        'es-AR',
        'es-BO',
        'es-CL',
        'es-CO',
        'es-CR',
        'es-DO',
        'es-EC',
        'es-ES',
        'es-GT',
        'es-HN',
        'es-MX',
        'es-NI',
        'es-PA',
        'es-PE',
        'es-PR',
        'es-PY',
        'es-SV',
        'es-US',
        'es-UY',
        'es-VE',
        'et-EE',
        'eu-ES',
        'fa-IR',
        'fi-FI',
        'fil-PH',
        'fo-FO',
        'fr-BE',
        'fr-CA',
        'fr-CH',
        'fr-FR',
        'fr-LU',
        'fr-MC',
        'fy-NL',
        'ga-IE',
        'gd-GB',
        'gl-ES',
        'gsw-FR',
        'gu-IN',
        'ha-Latn-NG',
        'he-IL',
        'hi-IN',
        'hr-BA',
        'hr-HR',
        'hsb-DE',
        'hu-HU',
        'hy-AM',
        'id-ID',
        'ig-NG',
        'ii-CN',
        'is-IS',
        'it-CH',
        'it-IT',
        'iu-Cans-CA',
        'iu-Latn-CA',
        'ja-JP',
        'ka-GE',
        'kk-KZ',
        'kl-GL',
        'km-KH',
        'kn-IN',
        'kok-IN',
        'ko-KR',
        'ky-KG',
        'lb-LU',
        'lo-LA',
        'lt-LT',
        'lv-LV',
        'mi-NZ',
        'mk-MK',
        'ml-IN',
        'mn-MN',
        'mn-Mong-CN',
        'moh-CA',
        'mr-IN',
        'ms-BN',
        'ms-MY',
        'mt-MT',
        'nb-NO',
        'ne-NP',
        'nl-BE',
        'nl-NL',
        'nn-NO',
        'nso-ZA',
        'oc-FR',
        'or-IN',
        'pa-IN',
        'pl-PL',
        'prs-AF',
        'ps-AF',
        'pt-BR',
        'pt-PT',
        'qut-GT',
        'quz-BO',
        'quz-EC',
        'quz-PE',
        'rm-CH',
        'ro-RO',
        'ru-RU',
        'rw-RW',
        'sah-RU',
        'sa-IN',
        'se-FI',
        'se-NO',
        'se-SE',
        'si-LK',
        'sk-SK',
        'sl-SI',
        'sma-NO',
        'sma-SE',
        'smj-NO',
        'smj-SE',
        'smn-FI',
        'sms-FI',
        'sq-AL',
        'sr-Cyrl-BA',
        'sr-Cyrl-CS',
        'sr-Cyrl-ME',
        'sr-Cyrl-RS',
        'sr-Latn-BA',
        'sr-Latn-CS',
        'sr-Latn-ME',
        'sr-Latn-RS',
        'sv-FI',
        'sv-SE',
        'sw-KE',
        'syr-SY',
        'ta-IN',
        'te-IN',
        'tg-Cyrl-TJ',
        'th-TH',
        'tk-TM',
        'tn-ZA',
        'tr-TR',
        'tt-RU',
        'tzm-Latn-DZ',
        'ug-CN',
        'uk-UA',
        'ur-PK',
        'uz-Cyrl-UZ',
        'uz-Latn-UZ',
        'vi-VN',
        'wo-SN',
        'xh-ZA',
        'yo-NG',
        'zh-CN',
        'zh-HK',
        'zh-MO',
        'zh-SG',
        'zh-TW',
        'zu-ZA',
    );

    foreach ($locales as $locale) {
        if ($languageCode . '-' . strtoupper($languageCode) === $locale) {
            return $locale;
        }
    }

    foreach ($locales as $locale) {
        if (strpos($locale, $languageCode) === 0) {
            return $locale;
        }
    }

    return null;
}
