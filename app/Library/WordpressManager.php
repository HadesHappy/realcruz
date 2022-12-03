<?php

namespace Acelle\Library;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Http;

class WordpressManager
{
    public $enpoint;

    public function request($options)
    {
        $uri = $this->endpoint . '/' . $options['path'];

        if (!isset($options['type']) || $options['type'] == 'get') {
            $response = Http::get($uri, (isset($options['data']) ? $options['data'] : []));
        }

        return $response;
    }

    public function __construct()
    {
        if (!defined('DB_NAME')) {
            $this->endpoint = config('wordpress.url') . '/wp-json/vbrand/v1';

            // ** MySQL settings - You can get this info from your web host ** //
            /** The name of the database for WordPress */
            define('DB_NAME', config('database.connections.wordpress.database'));

            /** MySQL database username */
            define('DB_USER', config('database.connections.wordpress.username'));

            /** MySQL database password */
            define('DB_PASSWORD', config('database.connections.wordpress.password'));

            /** MySQL hostname */
            define('DB_HOST', config('database.connections.wordpress.host') . ':' . config('database.connections.wordpress.port'));

            require_once base_path() . '/../wpbase/wp-load.php';
            require_once base_path() . '/../wpbase/wp-admin/includes/admin.php';

            // // Load from customer wp source code
            // require_once config('wordpress.path') . '/wp-load.php';
            // require_once config('wordpress.path') . '/wp-admin/includes/admin.php';
        }
    }

    public function getTemplates()
    {
        return json_decode($this->request([
            'path' => 'themes',
            'data' => [
                'token' => \Auth::user()->api_token,
            ],
        ])->body(), true);
    }

    public function activateTheme($id)
    {
        $theme = wp_get_theme($id);

        if (! $theme->exists() || ! $theme->is_allowed()) {
            throw new \Exception('The requested theme does not exist.');
        }

        switch_theme($theme->get_stylesheet());
    }

    public function getProductCategories()
    {
        $taxonomy     = 'product_cat';
        $orderby      = 'name';
        $hierarchical = 1;
        $empty        = 0;


        $args = array(
            'taxonomy'     => $taxonomy,
            'orderby'      => $orderby,
            'hierarchical' => $hierarchical,
            'hide_empty'   => $empty,
        );

        $all_categories = get_categories($args);

        $data = [];
        foreach ($all_categories as $cat) {
            $data[] = [
                'value' => $cat->term_id,
                'text' => $cat->name,
            ];
        }

        return $data;
    }
}
