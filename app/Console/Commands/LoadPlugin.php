<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;

class LoadPlugin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:load {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load a plugin. For example: php artisan plugin:load awesome/hello';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        \Acelle\Model\Plugin::installFromDir($name);

        echo "\e[32mPlugin \e[35m{$name}\033[0m \e[32mloaded!\n\033[0m";
    }
}
