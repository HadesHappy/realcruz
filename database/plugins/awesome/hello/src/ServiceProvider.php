<?php

namespace {{ author_class }}\{{ name_class }};

use Illuminate\Support\ServiceProvider as Base;
use Acelle\Library\Facades\Hook;

class ServiceProvider extends Base
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Register views path
        $this->loadViewsFrom(__DIR__.'/../resources/views', '{{ name }}');

        // Register routes file
        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        // Register translation file
        $this->loadTranslationsFrom(storage_path('app/data/plugins/{{ author }}/{{ name }}/lang/'), '{{ name }}');

        // Register the translation file against Acelle translation management
        Hook::register('add_translation_file', function() {
            return [
                "id" => '#{{ plugin }}_translation_file',
                "plugin_name" => "{{ plugin }}",
                "file_title" => "Translation for {{ plugin }} plugin",
                "translation_folder" => storage_path('app/data/plugins/{{ author }}/{{ name }}/lang/'),
                "file_name" => "messages.php",
                "master_translation_file" => realpath(__DIR__.'/../resources/lang/en/messages.php'),
            ];
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
