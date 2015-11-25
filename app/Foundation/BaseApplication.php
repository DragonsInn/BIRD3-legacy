<?php namespace BIRD3\Foundation;

// This is modified code from:
// https://github.com/caffeinated/beverage/blob/master/src/Application.php

use \Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Events\EventServiceProvider;

abstract class BaseApplication extends LaravelApplication {
    // Holds all the paths
    protected $_paths;

    /**
      * Bootstrap the application for HTTP requests.
     *
     * @return void
     */
    public function bootstrapConsole() {
        $bootstrappers = [
            'Illuminate\Foundation\Bootstrap\DetectEnvironment',
            'Illuminate\Foundation\Bootstrap\LoadConfiguration',
            'Illuminate\Foundation\Bootstrap\ConfigureLogging',
            'Illuminate\Foundation\Bootstrap\HandleExceptions',
            'Illuminate\Foundation\Bootstrap\RegisterFacades',
            'Illuminate\Foundation\Bootstrap\SetRequestForConsole',
            'Illuminate\Foundation\Bootstrap\RegisterProviders',
            'Illuminate\Foundation\Bootstrap\BootProviders',
        ];
        if(!$this->hasBeenBootstrapped()) {
            $this->bootstrapWith($bootstrappers);
        }
    }

    public function __construct($basePath, $configPath = null) {
        $this->_paths = $this->loadConfig($configPath);
        require_once __DIR__."/../Support/Helpers.php";
        parent::__construct($basePath);
    }

    /**
     * Get the path to the application's 'app' folder.
     *
     * @return string
     */
    public function path() {
        return $this->basePath.DIRECTORY_SEPARATOR.$this->_paths['app_path'];
    }

    /**
     * Get the path to the application configuration files.
     *
     * @return string
     */
    public function configPath() {
        return $this->basePath.DIRECTORY_SEPARATOR.$this->_paths['config_path'];
    }

    /**
     * Get the path to the database directory.
     *
     * @return string
     */
    public function databasePath() {
        return $this->basePath.DIRECTORY_SEPARATOR.$this->_paths['database_path'];
    }

    /**
     * Get the path to the language files.
     *
     * @return string
     */
    public function langPath() {
        return $this->basePath.DIRECTORY_SEPARATOR.$this->_paths['lang_path'];
    }

    /**
     * Get the path to the public / web directory.
     *
     * @return string
     */
    public function publicPath() {
        return $this->basePath.DIRECTORY_SEPARATOR.$this->_paths['public_path'];
    }

    /**
     * Get the path to the storage directory.
     *
     * @return string
     */
    public function storagePath() {
        return $this->basePath.DIRECTORY_SEPARATOR.$this->_paths['storage_path'];
    }

    /**
     * Get the path to the cached services.json file.
     *
     * @return string
     */
    public function getCachedServicesPath() {
        return $this->path().'/../cache/services.json';
    }

    /**
     * Get the path to the cached "compiled.php" file.
     *
     * @return string
     */
    public function getCachedCompilePath() {
        return $this->path().'/../cache/compiled.php';
    }

    /**
     * Manually load our beverage config file. We need to do this since this
     * file is loaded before the config service provider is kicked in.
     *
     * @return array
     */
    protected function loadConfig($customConfigPath = null) {
        if (is_null($customConfigPath)) {
            $customConfigPath = $this->basePath.'/config';
        }

        // Load the paths config
        $customConfigFile = $customConfigPath.'/paths.php';
        $config = include_once($customConfigFile);

        return $config["custom_paths"];
    }
}
