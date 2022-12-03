<?php

namespace Acelle\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use URL;
use Acelle\Model\Setting;
use Acelle\Library\HookManager;
use Acelle\Model\Plugin;
use Acelle\Model\Notification;
use Acelle\Library\BillingManager;
use Acelle\Library\Facades\Hook;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Teak default settings (PHP, Laravel, etc.)
        $this->changeDefaultSettings();

        // Add custom validation rules
        // @deprecated
        $this->addCustomValidationRules();

        // Just finish if the application is not set up
        if (!isInitiated()) {
            return;
        }

        // Load application's plugins
        // Disabled plugin may also register hooks
        $this->loadPlugins();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(HookManager::class, function ($app) {
            return new HookManager();
        });

        $this->app->singleton(BillingManager::class, function ($app) {
            return new BillingManager();
        });

        Hook::register('add_translation_file', function () {
            return [
                "id" => "acelle_messages",
                "plugin_name" => "Application/Core",
                "file_title" => "Messages",
                "translation_folder" => base_path('resources/lang'),
                "file_name" => "messages.php",
                "master_translation_file" => resource_path('lang/default/messages.php'),
            ];
        });

        Hook::register('add_translation_file', function () {
            return [
                "id" => "acelle_auth",
                "plugin_name" => "Application/Core",
                "file_title" => "Auth",
                "translation_folder" => base_path('resources/lang'),
                "file_name" => "auth.php",
                "master_translation_file" => resource_path('lang/default/auth.php'),
            ];
        });

        Hook::register('add_translation_file', function () {
            return [
                "id" => "acelle_pagination",
                "plugin_name" => "Application/Core",
                "file_title" => "Pagination",
                "translation_folder" => base_path('resources/lang'),
                "file_name" => "pagination.php",
                "master_translation_file" => resource_path('lang/default/pagination.php'),
            ];
        });

        Hook::register('add_translation_file', function () {
            return [
                "id" => "acelle_passwords",
                "plugin_name" => "Application/Core",
                "file_title" => "Passwords",
                "translation_folder" => base_path('resources/lang'),
                "file_name" => "passwords.php",
                "master_translation_file" => resource_path('lang/default/passwords.php'),
            ];
        });

        Hook::register('add_translation_file', function () {
            return [
                "id" => "acelle_builder",
                "plugin_name" => "Application/Core",
                "file_title" => "Builder",
                "translation_folder" => base_path('resources/lang'),
                "file_name" => "builder.php",
                "master_translation_file" => resource_path('lang/default/builder.php'),
            ];
        });

        Hook::register('add_translation_file', function () {
            return [
                "id" => "acelle_validation",
                "plugin_name" => "Application/Core",
                "file_title" => "Validation",
                "translation_folder" => base_path('resources/lang'),
                "file_name" => "validation.php",
                "master_translation_file" => resource_path('lang/default/validation.php'),
            ];
        });

        // default captcha method
        Hook::register('captcha_method', function () {
            return [
                "id" => 'recaptcha',
                "title" => trans('messages.recaptcha'),
            ];
        });
    }

    private function loadPlugins()
    {
        try {
            Plugin::autoload();
            Notification::cleanupDuplicateNotifications('Plugin Error');
        } catch (\Exception $ex) {
            // Just in case
            Notification::warning([
                'message' => 'Cannot load Acelle plugins. Error: '.htmlspecialchars($ex->getMessage()),
                'title' => 'Plugin Error',
            ]);
        }
    }

    // @deprecated
    private function addCustomValidationRules()
    {
        // extend substring validator
        Validator::extend('substring', function ($attribute, $value, $parameters, $validator) {
            $tag = $parameters[0];
            if (strpos($value, $tag) === false) {
                return false;
            }

            return true;
        });
        Validator::replacer('substring', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':tag', $parameters[0], $message);
        });

        // License validator
        Validator::extend('license', function ($attribute, $value, $parameters, $validator) {
            return $value == '' || true;
        });

        // License error validator
        Validator::extend('license_error', function ($attribute, $value, $parameters, $validator) {
            return false;
        });
        Validator::replacer('license_error', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':error', $parameters[0], $message);
        });
    }

    private function changeDefaultSettings()
    {
        ini_set('memory_limit', '-1');
        ini_set('pcre.backtrack_limit', 1000000000);

        // Laravel 5.5 to 5.6 compatibility
        Blade::withoutDoubleEncoding();

        // Check if HTTPS (including proxy case)
        $isSecure = false;
        if (isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') == 0) {
            $isSecure = true;
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') == 0 || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_SSL'], 'on') == 0) {
            $isSecure = true;
        }

        if ($isSecure) {
            URL::forceScheme('https');
        }

        // HTTP or HTTPS
        // parse_url will return either 'http' or 'https'
        //$scheme = parse_url(config('app.url'), PHP_URL_SCHEME);
        //if (!empty($scheme)) {
        //    URL::forceScheme($scheme);
        //}

        // Fix Laravel 5.4 error
        // [Illuminate\Database\QueryException]
        // SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes
        Schema::defaultStringLength(191);

        if (!\App::runningInConsole()) {
            // This is just a trick for getting Controller name in view
            // See https://stackoverflow.com/questions/29549660/get-laravel-5-controller-name-in-view
            // @todo: fix this anti-pattern
            app('view')->composer('*', function ($view) {
                $route = app('request')->route();
                if (is_null($route)) {
                    return;
                }

                $action = app('request')->route()->getAction();

                if (!array_key_exists('controller', $action)) {
                    return;
                }

                $controller = class_basename($action['controller']);
                list($controller, $action) = explode('@', $controller);
                $view->with(compact('controller', 'action'));
            });
        }
    }
}
