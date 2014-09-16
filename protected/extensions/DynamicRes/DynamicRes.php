<?php

require_once('CssMin.php');
require_once('JavaScriptPacker.php');

class DynamicRes extends CWidget {
    private $_urlConfig = array( // Configurar conversão Url To Path
        'baseUrl'  => 'http://meusite.com/',
        'basePath' => '/home/meusite/', // Absolete
    );
	private $errors = array();
	private $jsList = array();
	private $cssList = array();
	
	public function setUrlConfig($valor) {
		if(!is_dir($valor['basePath'])) $this->errors[] = 'Wrong Config: Your basePath is invalid (not exixts)';
		$baseUrlArr = str_split($valor['baseUrl']);
		if(end($baseUrlArr)!='/') $this->errors[] = 'Wrong Config: Your baseUrl is invalid (need to end with /)';
		$this->_urlConfig = array(
			'baseUrl'  => $valor['baseUrl'],
			'basePath' => realpath($valor['basePath']) . '/',
		);
	}
	
	public function getUrlConfig() {
		return $this->_urlConfig;
	}
	
	public function debug() {
		echo "dynamicRes: FUNCTION DEBUG CALLED \n <br />";
		if(count($this->jsList)>0 || count($this->cssList)>0) {
			$this->errors[] = 'You need to call function "saveScheme".';
		}
		
		if(count($this->errors)==0) {
			echo "all is ok";
		} else foreach($this->errors as $error) {
			echo "- $error <br /> \n";
		}
		
		exit();
	}
    
    public function getCachePath() {
        return Yii::app()->assetManager->basePath;
    }
    
    public function getUrlPath() {
        return Yii::app()->assetManager->baseUrl;
    }
    
    function urlToPath($url) { 
        $dS = DIRECTORY_SEPARATOR;
        //$from = preg_quote($this->urlConfig['baseUrl'], '/');
		$from = preg_quote(Yii::app()->request->baseUrl, '/');
        $to  = preg_quote(str_replace('\\' , '/' , $this->urlConfig['basePath']), '/');
        $result = preg_replace("/$from/", "/$to/", $url, 1);
        $result = str_replace('/' , $dS , str_replace('//','/',stripcslashes($result))); // fix windows
        if($result[0]=='\\') $result = substr($result, 1); // fix windows
        return $result;
    }
    
    function parserCss($url, $content) {
        $newContent = $content;
        $aux = explode('/',$url);
        $dir = preg_quote(implode('/', array_slice($aux,0,count($aux)-1)));
        $newContent = preg_replace('@url\((["\'])?([^/])@',"url($1$dir/$2", $newContent);
        return $newContent;
    }
	
	private function registerFile(&$array, $name) {
		if(file_exists($this->urlToPath($name))) {
			$array[] = $name;
			return true;
		} else {
			$this->errors[] = "File {$this->urlToPath($name)} dont exists.";
			return false;
		}
	}
	
	public function registerCssFile($urlName) {
		return $this->registerFile($this->cssList, $urlName);
	}
	
	public function registerScriptFile($urlName) {
		return $this->registerFile($this->jsList, $urlName);
	}
	
	private function saveFileScheme(&$array, $extension) {
		$virtualFiles = array();
		foreach($array as $name) {
            $realName = $this->urlToPath($name);
			$content = file_get_contents($realName);
			$hash = md5($content);
			$nomeAux = explode(DIRECTORY_SEPARATOR, $name);
			$virtualFiles[] = array(
                'urlName' => $name,
                'pathName' => $realName,
				'content' => $content,
				'hash' => $hash,
				'nome' => end($nomeAux),
                'extension' => strtolower($extension),
			);
		}
		$generatedName = $this->generateName($virtualFiles, $extension);
		$name = $this->getCachePath() . DIRECTORY_SEPARATOR . $generatedName;
		$urlName = $this->getUrlPath() . '/' . $generatedName;
		if(!file_exists($name)) {
			$this->generateFile($virtualFiles, $name);
			return $urlName;
		} else return $urlName; 
	}
	
	private function generateFile($virtualFiles, $name) {
		$finalContent = '';
		foreach($virtualFiles as $virtualFile) {
            $hoje = utf8_decode(date('d/m/y \á\s H:i:s'));
			$finalContent .= "/* START OF FILE {$virtualFile['nome']} (Comprimido $hoje)------------ */ \n";
            $thisContent = $this->compressContent($virtualFile['content'], $virtualFile['extension']);
            if($virtualFile['extension']=='css') $thisContent = $this->parserCss($virtualFile['urlName'], $thisContent);
			$finalContent .= $thisContent;
			$finalContent .= "\n/* END OF FILE {$virtualFile['nome']} ------------------------ */\n";
		}
        
		file_put_contents($name, $finalContent);
		return $name;
	}
	
	private function generateName($virtualFiles, $extension) {
		$total_hash = '';
		foreach($virtualFiles as $array) $total_hash .= $array['hash'];
		return md5($total_hash) . ".$extension";
	}
	
	private function compressContent($content, $type) {
        if($type=='css') {
            return CssMin::minify($content, array(), array('CompressUnitValues' => true));
        } else if($type=='js') { // is js
            $jsSize = strlen($content);
            if($jsSize > 256 && $jsSize < 1048576) { // prevent memory error 
                $packer = new JavaScriptPacker($content, 'Normal', true, false);
                return $packer->pack();
            } else {
                return $content;
            }
        } else {
            //dont Compress
            return $content;
        }
		
	}
	
	public function saveScheme() {
        if(count($this->jsList)>0) {
            $fileJs = $this->saveFileScheme($this->jsList, 'js');
            Yii::app()->clientScript->registerScriptFile($fileJs);
        }
        if(count($this->cssList)>0) {
            $fileCss = $this->saveFileScheme($this->cssList, 'css');
            Yii::app()->clientScript->registerCssFile($fileCss);
        }
        $this->jsList = array();
        $this->cssList = array();
	}
}
