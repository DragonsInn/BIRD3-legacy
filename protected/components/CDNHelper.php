<?php class CDNHelper extends CApplicationComponent {
    private $_basePath;
    private $_baseUrl;

    public function setBasePath($p) { $this->_basePath = $p; }
    public function setBaseUrl($p) { $this->_baseUrl = $p; }

    public function getBasePath() { return $this->_basePath; }
    public function getBaseUrl() { return $this->_baseUrl; }
}
