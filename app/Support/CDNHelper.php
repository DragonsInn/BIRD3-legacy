<?php namespace BIRD3\Support;

class CDN {
    private $_basePath;
    private $_baseUrl;

    public function setBasePath($p) { $this->_basePath = $p; }
    public function setBaseUrl($p) { $this->_baseUrl = $p; }

    public function getBasePath() { return $this->_basePath; }
    public function getBaseUrl() { return $this->_baseUrl; }

    public function css($file) {
        # Register CSS file to the output
        return $this;
    }

    public function js($file) {
        # Register a JS file.
        return $this;
    }

    public function alt($type, $path) {
        # Register a file by name, independent of CDN base path.
        return $this;
    }
}
