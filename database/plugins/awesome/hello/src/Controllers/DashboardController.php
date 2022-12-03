<?php

namespace {{ author_class }}\{{ name_class }}\Controllers;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller as BaseController;
use Acelle\Model\Plugin;

class DashboardController extends BaseController
{
    public function index(Request $request)
    {
        // Get the plugin record in the plugin table
        $plugin = Plugin::where('name', '{{ plugin }}')->first();

        // View files are available in the storage/app/plugins/{{ author }}/{{ name }}/resources/views/ folder
        // Remember to use the {{ name }}:: prefix for specifying view
        return view('{{ name }}::index', [
            'plugin' => $plugin,
        ]);
    }
}
