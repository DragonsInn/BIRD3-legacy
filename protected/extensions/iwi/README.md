iwi
========

Idol Web Image module for adaptive image resizing and caching in database, based on Kohana Image module.

Next methods are available:
* crop()
* rotate()
* flip()
* sharpen()
* quality()
* render()
* save()

[Detailed documentation](http://docs.kohanaphp.com/libraries/image)

* cache()
* adaptive()

[Additional tags](https://github.com/Idol-IT/iwi/wiki/Additional-tags)


Installation
=========
1.Place 'iwi' folder into 'protected/extensions/'

2.Configuration (protected/config/main.php):

    'iwi' => array(
         'class' => 'application.extensions.iwi.IwiComponent',
         // GD or ImageMagick
         'driver' => 'GD',
         // ImageMagick setup path
         //'params'=>array('directory'=>'C:/ImageMagick'),
    ),


Usage
====================

    // loading
    Yii::import('ext.iwi.Iwi');
    $picture = new Iwi('images/sample.jpg');
    $picture->resize(100,100, Iwi::NONE);
    echo $picture->cache();

    // chainable usage in template
    echo Yii::app()->iwi->load("images/totem.png")->adaptive(250,120)->cache();


Deprecated
====================
    $this->widget("ext.iwi.Resize");

Widget Resize is now derpecated.  
Use `adaptive()` method now.



Changelog
=====================
### November 03, 2012
* Added support of tablePrefix. Requested by the user: kernel32ddl
* Fix error if image doesn't exist

#### July 25, 2012
* Recreating cache if cache is missing
* If the original image is overwritten, the cache will be changed

#### May 17, 2012
* `adaptive()` combination of resize & crop, helps to making thumbnail.

#### May 15, 2012

* Version 1.1 release
* New api
* ImageMagick support
* There is no need to perform a dump, it is performed automatically


#### May 10, 2012

* SQLite database support


#### May 4, 2012

* Release of 1.0 version