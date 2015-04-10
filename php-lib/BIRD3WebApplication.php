<?php class BIRD3WebApplication extends CApplication {

    // This is an EXACT copy of CWebApplication...BUT. It does not immediately
    // calculate the request...

    // THIS is the only reason why I am doing this...
    /*protected function pre_init() {
        parent::init();
    }*/


    public $defaultController='site';
    public $layout='main';
    public $controllerMap=array();
    public $catchAllRequest;
    public $controllerNamespace;

    private $_controllerPath;
    private $_viewPath;
    private $_systemViewPath;
    private $_layoutPath;
    private $_controller;
    private $_theme;

    public function processRequest()
    {
        if(is_array($this->catchAllRequest) && isset($this->catchAllRequest[0]))
        {
            $route=$this->catchAllRequest[0];
            foreach(array_splice($this->catchAllRequest,1) as $name=>$value)
                $_GET[$name]=$value;
        }
        else
            $route=$this->getUrlManager()->parseUrl($this->getRequest());
            Log::info("Route is $route");
        $this->runController($route);
    }

    protected function registerCoreComponents()
    {
        parent::registerCoreComponents();

        $components=array(
            'session'=>array(
                'class'=>'CHttpSession',
            ),
            'assetManager'=>array(
                'class'=>'CAssetManager',
            ),
            'user'=>array(
                'class'=>'CWebUser',
            ),
            'themeManager'=>array(
                'class'=>'CThemeManager',
            ),
            'authManager'=>array(
                'class'=>'CPhpAuthManager',
            ),
            'clientScript'=>array(
                'class'=>'CClientScript',
            ),
            'widgetFactory'=>array(
                'class'=>'CWidgetFactory',
            ),
        );

        $this->setComponents($components);
    }

    public function getAuthManager()
    {
        return $this->getComponent('authManager');
    }

    public function getAssetManager()
    {
        return $this->getComponent('assetManager');
    }

    public function getSession()
    {
        return $this->getComponent('session');
    }

    public function getUser()
    {
        return $this->getComponent('user');
    }

    public function getViewRenderer()
    {
        return $this->getComponent('viewRenderer');
    }

    public function getClientScript()
    {
        return $this->getComponent('clientScript');
    }

    public function getWidgetFactory()
    {
        return $this->getComponent('widgetFactory');
    }

    public function getThemeManager()
    {
        return $this->getComponent('themeManager');
    }

    public function getTheme()
    {
        if(is_string($this->_theme))
            $this->_theme=$this->getThemeManager()->getTheme($this->_theme);
        return $this->_theme;
    }

    public function setTheme($value)
    {
        $this->_theme=$value;
    }

    public function runController($route)
    {
        if(($ca=$this->createController($route))!==null)
        {
            list($controller,$actionID)=$ca;
            $oldController=$this->_controller;
            $this->_controller=$controller;
            $controller->init();
            $controller->run($actionID);
            $this->_controller=$oldController;
        }
        else
            throw new CHttpException(404,Yii::t('yii','Unable to resolve the request "{route}".',
                array('{route}'=>$route===''?$this->defaultController:$route)));
    }

    public function createController($route,$owner=null)
    {
        if($owner===null)
            $owner=$this;
        if(($route=trim($route,'/'))==='')
            $route=$owner->defaultController;
        $caseSensitive=$this->getUrlManager()->caseSensitive;

        $route.='/';
        while(($pos=strpos($route,'/'))!==false)
        {
            $id=substr($route,0,$pos);
            if(!preg_match('/^\w+$/',$id))
                return null;
            if(!$caseSensitive)
                $id=strtolower($id);
            $route=(string)substr($route,$pos+1);
            if(!isset($basePath))  // first segment
            {
                if(isset($owner->controllerMap[$id]))
                {
                    return array(
                        Yii::createComponent($owner->controllerMap[$id],$id,$owner===$this?null:$owner),
                        $this->parseActionParams($route),
                    );
                }

                if(($module=$owner->getModule($id))!==null)
                    return $this->createController($route,$module);

                $basePath=$owner->getControllerPath();
                $controllerID='';
            }
            else
                $controllerID.='/';
            $className=ucfirst($id).'Controller';
            $classFile=$basePath.DIRECTORY_SEPARATOR.$className.'.php';

            if($owner->controllerNamespace!==null)
                $className=$owner->controllerNamespace.'\\'.str_replace('/','\\',$controllerID).$className;

            if(is_file($classFile))
            {
                if(!class_exists($className,false))
                    require($classFile);
                if(class_exists($className,false) && is_subclass_of($className,'CController'))
                {
                    $id[0]=strtolower($id[0]);
                    return array(
                        new $className($controllerID.$id,$owner===$this?null:$owner),
                        $this->parseActionParams($route),
                    );
                }
                return null;
            }
            $controllerID.=$id;
            $basePath.=DIRECTORY_SEPARATOR.$id;
        }
    }

    protected function parseActionParams($pathInfo)
    {
        if(($pos=strpos($pathInfo,'/'))!==false)
        {
            $manager=$this->getUrlManager();
            $manager->parsePathInfo((string)substr($pathInfo,$pos+1));
            $actionID=substr($pathInfo,0,$pos);
            return $manager->caseSensitive ? $actionID : strtolower($actionID);
        }
        else
            return $pathInfo;
    }

    public function getController()
    {
        return $this->_controller;
    }

    public function setController($value)
    {
        $this->_controller=$value;
    }

    public function getControllerPath()
    {
        if($this->_controllerPath!==null)
            return $this->_controllerPath;
        else
            return $this->_controllerPath=$this->getBasePath().DIRECTORY_SEPARATOR.'controllers';
    }

    public function setControllerPath($value)
    {
        if(($this->_controllerPath=realpath($value))===false || !is_dir($this->_controllerPath))
            throw new CException(Yii::t('yii','The controller path "{path}" is not a valid directory.',
                array('{path}'=>$value)));
    }

    public function getViewPath()
    {
        if($this->_viewPath!==null)
            return $this->_viewPath;
        else
            return $this->_viewPath=$this->getBasePath().DIRECTORY_SEPARATOR.'views';
    }

    public function setViewPath($path)
    {
        if(($this->_viewPath=realpath($path))===false || !is_dir($this->_viewPath))
            throw new CException(Yii::t('yii','The view path "{path}" is not a valid directory.',
                array('{path}'=>$path)));
    }

    public function getSystemViewPath()
    {
        if($this->_systemViewPath!==null)
            return $this->_systemViewPath;
        else
            return $this->_systemViewPath=$this->getViewPath().DIRECTORY_SEPARATOR.'system';
    }

    public function setSystemViewPath($path)
    {
        if(($this->_systemViewPath=realpath($path))===false || !is_dir($this->_systemViewPath))
            throw new CException(Yii::t('yii','The system view path "{path}" is not a valid directory.',
                array('{path}'=>$path)));
    }

    public function getLayoutPath()
    {
        if($this->_layoutPath!==null)
            return $this->_layoutPath;
        else
            return $this->_layoutPath=$this->getViewPath().DIRECTORY_SEPARATOR.'layouts';
    }

    public function setLayoutPath($path)
    {
        if(($this->_layoutPath=realpath($path))===false || !is_dir($this->_layoutPath))
            throw new CException(Yii::t('yii','The layout path "{path}" is not a valid directory.',
                array('{path}'=>$path)));
    }

    public function beforeControllerAction($controller,$action)
    {
        return true;
    }

    public function afterControllerAction($controller,$action)
    {
    }

    public function findModule($id)
    {
        if(($controller=$this->getController())!==null && ($module=$controller->getModule())!==null)
        {
            do
            {
                if(($m=$module->getModule($id))!==null)
                    return $m;
            } while(($module=$module->getParentModule())!==null);
        }
        if(($m=$this->getModule($id))!==null)
            return $m;
    }
}
