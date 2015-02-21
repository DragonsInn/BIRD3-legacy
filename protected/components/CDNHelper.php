<?php class CDNHelper extends CApplicationComponent {
    private $_basePath;
    private $_baseUrl;

    public function setBasePath($p) { $this->_basePath = $p; }
    public function setBaseUrl($p) { $this->_baseUrl = $p; }

    public function getBasePath() { return $this->_basePath; }
    public function getBaseUrl() { return $this->_baseUrl; }

    public function css($file) {
        Yii::app()->clientScript->registerCssFile(
            $this->getBaseUrl()."/css/".$file
        );
        return $this;
    }

    public function js($file) {
        Yii::app()->clientScript->registerScriptFile(
            $this->getBaseUrl()."/js/".$file
        );
        return $this;
    }

    public function alt($type, $path) {
        $cs = Yii::app()->clientScript;
        $mt = "";
        switch(strtolower($type)) {
            case "js":
                $mt = "registerScriptFile";
            break;
            case "css":
                $mt = "registerCssFile";
            break;
            default: return $this;
        }
        $cs->$mt($this->getBaseUrl().$path);
        return $this;
    }

    public function avvie($id) {
        return join(DIRECTORY_SEPARATOR, [$this->basePath, "content", "avatars", $id]);
    }
}
