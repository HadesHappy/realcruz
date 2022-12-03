<?php

namespace Acelle\Library;

use Exception;

class HookManager
{
    protected $hooks;
    protected $plugins;

    public function __construct()
    {
        $this->hooks = [];
    }

    public function register($name, $callback)
    {
        // Example:
        // $manager->registerHook('hello', function($param1, $param2) { ... });
        // $manager->executeHook('hello', [ $param1, $param2 ]);
        if ($this->isEmpty($name)) {
            $this->hooks[$name] = [$callback];
        } else {
            $this->hooks[$name][] = $callback;
        }
    }

    public function registerIfEmpty($name, $callback)
    {
        if ($this->isEmpty($name)) {
            $this->hooks[$name] = [$callback];
        }
    }

    // Execute hook's registered closed, return a list of execution results
    public function execute($name, $params = [])
    {
        $results = [];
        if (!$this->isEmpty($name)) {
            foreach ($this->hooks[$name] as $callback) {
                $results[] = call_user_func_array($callback, $params);
            }
        }

        return $results;
    }

    // Execute the last closure in the hook and capture the result
    public function perform($name, $params = [])
    {
        if ($this->isEmpty($name)) {
            throw new Exception("Cannot perform empty hook \"{$name}\"");
        }

        $closures = $this->hooks[$name];
        $lastClosure = end($closures);
        return call_user_func_array($lastClosure, $params);
    }

    public function isEmpty($name)
    {
        return ! array_key_exists($name, $this->hooks);
    }

    public function installFromDir($name)
    {
        $composer = $this->getComposerJson($name);
        $record = PluginModel::createFromComposerJson($composer);
    }
}
