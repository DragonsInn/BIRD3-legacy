<?php

Yii::import('application.extensions.iwi.vendors.image.Image');
Yii::import('application.extensions.iwi.models.Storage');

class Iwi extends Image
{
    /**
     * @param $width
     * @param $height
     * @param bool $upscale
     * @return Iwi|object
     */
    public function adaptive($width, $height, $upscale = false)
    {

        if ($this->image) {

            if (!$upscale) {
                if ($width > $this->image["width"])
                    $width = $this->image["width"];

                if ($height > $this->image["height"])
                    $height = $this->image["height"];
            }

            $width = intval($width);
            $height = intval($height);

            $widthProportion = $width / $this->image["width"];
            $heightProportion = $height / $this->image["height"];

            if ($widthProportion > $heightProportion) {
                $newWidth = $width;
                $newHeight = round($newWidth / $this->image["width"] * $this->image["height"]);
            } else {
                $newHeight = $height;
                $newWidth = round($newHeight / $this->image["height"] * $this->image["width"]);
            }

            $this->resize($newWidth, $newHeight);

            return $this->crop($width, $height, "center");

        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function cache()
    {
        $path = $this->buildPath();
        if ($path) {
            if ($this->createOrNone() || !file_exists($path)) {
                $this->save($path);
            }
        }
        return Yii::app()->createUrl($path);
    }

    /**
     * @return bool|string
     */
    public function buildPath()
    {
        if (!isset($this->image["file"])) {
            return false;
        }
        $path = array();
        $info = pathinfo($this->image["file"]);
        $path[] = $this->buildDir();
        $path[] = $this->hash() . "." . $info['extension'];
        return implode("/", $path);
    }

    /**
     * @return string
     */
    public function buildDir()
    {
        $folder[] = YiiBase::getPathOfAlias('webroot.images.site.cache');
        $folder[] = substr($this->hash(), 0, 2);
        $folder[] = substr($this->hash(), 2, 2);

        $path[] = "images/site/cache";
        $path[] = substr($this->hash(), 0, 2);
        $path[] = substr($this->hash(), 2, 2);

        $path = implode("/", $path);
        $folder = implode("/", $folder);

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        return $path;
    }

    /**
     * @return string
     */
    public function hash()
    {
        return md5($this->generateBrief());
    }

    /**
     * @return string
     */
    protected function generateBrief()
    {
        $needle = $this->actions;
        array_unshift($needle, $this->image["file"]);
        if (is_file($this->image["file"]))
            array_unshift($needle, filemtime($this->image["file"]));
        return json_encode($needle);
    }

    /**
     * @return bool
     */
    public function createOrNone()
    {
        $this->verifyTable();

        if (!Storage::model()->findByAttributes(array('key' => $this->hash()))) {
            $storage = new Storage();
            $storage->key = $this->hash();
            $storage->value = json_encode($this->generateBrief());
            return $storage->save();
        }
        return false;
    }

    /**
     * Verify table
     */
    public function verifyTable()
    {
        if (!Yii::app()->getDb()->schema->getTable('{{storage}}')) {
            Yii::app()->getDb()->createCommand()->createTable("{{storage}}", array(
                'key' => 'string',
                'value' => 'text',
            ));
        }
    }

    /**
     * @param $image
     * @param null $config
     */
    // changed exception
    public function __construct($image, $config = NULL)
    {
        static $check;

        // Make the check exactly once
        ($check === NULL) and $check = function_exists('getimagesize');

        if ($check === FALSE)
            throw new CException('image getimagesize missing');

        // Check to make sure the image exists
        if (!is_file($image))
            return $this;

        // Disable error reporting, to prevent PHP warnings
        $ER = error_reporting(0);

        // Fetch the image size and mime type
        $image_info = getimagesize($image);

        // Turn on error reporting again
        error_reporting($ER);

        // Make sure that the image is readable and valid
        if (!is_array($image_info) OR count($image_info) < 3)
            throw new CException('image file unreadable');

        // Check to make sure the image type is allowed
        if (!isset(Image::$allowed_types[$image_info[2]]))
            throw new CException('image type not allowed');

        // Image has been validated, load it
        $this->image = array
        (
            'file' => str_replace('\\', '/', realpath($image)),
            'width' => $image_info[0],
            'height' => $image_info[1],
            'type' => $image_info[2],
            'ext' => Image::$allowed_types[$image_info[2]],
            'mime' => $image_info['mime']
        );

        // Load configuration
        if ($config === null) {
            $this->config = array(
                'driver' => 'GD',
                'params' => array(),
            );
        } else {
            $this->config = $config;
        }

        // Set driver class name
        $driver = 'Image_' . ucfirst($this->config['driver']) . '_Driver';

        // Load the driver
        Yii::import("application.extensions.iwi.vendors.image.drivers.$driver");

        // Initialize the driver
        $this->driver = new $driver($this->config['params']);

        // Validate the driver
        if (!($this->driver instanceof Image_Driver))
            throw new CException('image driver must be implement Image_Driver class');
    }


}