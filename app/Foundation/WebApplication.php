<?php namespace BIRD3\Foundation;

use BIRD3\Foundation\BaseApplication;

class WebApplication extends BaseApplication {
    public function __construct($basePath = null, $configPath = null) {
        parent::__construct($basePath, $configPath);
    }
}
