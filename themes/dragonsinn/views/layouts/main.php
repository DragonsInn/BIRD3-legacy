<!DOCTYPE html>
<html>
    <head>
        <?php // PHP logic
            $meta_desc = "The Dragon's Inn is a cozy place for furry and non-furry"
                ." roleplayers as well as casual chatters."
                ." Stop by and hang out with artists and freaks! =)";
            $pageTitle = Yii::app()->name.": ".$this->pageTitle;
            $this->registerScripts();
            $cdn = Yii::app()->cdn->baseUrl;
        ?>

        <title><?=$pageTitle?></title>

        <!-- casual -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
        <meta name="description" content="<?=$meta_desc?>"/>
        <!-- Facebook -->
        <meta property="og:title" content="<?=$pageTitle?>"/>
        <meta property="og:type" content="<?=$this->og_type?>"/>
        <meta property="og:image" content="<?=$this->og_image?>"/>
        <meta property="og:url" content="<?=Yii::app()->request->url?>"/>
        <meta property="og:description" content="<?=$meta_desc?>"/>
        <meta property="fb:admins" content="SexyXynu"/>
        <!-- Twitter -->
        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:url" content="<?=Yii::app()->request->url?>"/>
        <meta name="twitter:title" content="<?=$pageTitle?>"/>
        <meta name="twitter:description" content="<?=$meta_desc?>"/>
        <meta name="twitter:image" content="<?=$this->og_image?>"/>

        <script>
            var useBottomPanel = <?=($this->panelBottom?"true":"false")?>;
        </script>
    </head>
    <body class="panel-pusher">
        <!-- Panels -->
        <div id="Ptop" class="panel-default panel-top">
        </div>
        <div id="Pleft" class="panel-default panel-side panel-left">
            <div>
                <input type="search"
                    name="search"
                    class="form-control white-box"
                    placeholder="Search/Command..."
                    aria-label="Type search term or command"
                />
            </div>
            <div id="searchResults" aria-label="Search results">
            </div>
        </div>
        <div id="Pright" class="panel-default panel-side panel-right">
            <?php $this->widget("BIRD3LoginWidget"); ?>
            <hr>
            <div>
                BIRD@<?=Yii::app()->params['version']?>
            </div>
        </div>
        <?php if($this->panelBottom != false) { ?>
        <div id="Pbottom" class="panel-default panel-bottom">
            <?=$this->panelBottom?>
        </div>
        <?php } ?>

        <!-- Menu, bottom part -->
        <div id="menu">
            <div class="tabbable tabs-below tabs-multi">
                <ul class="nav nav-tabs">
                    <li id="trigger-left">
                        <div class="circle circle-small">
                            <i class="fa fa-search"></i>
                        </div>
                    </li>
                </ul>
                <ul class="nav nav-tabs" id="menu-tabs">
		            <li class="show-onMedium active"><a id="trigger-top" href="#">The Inn</a></li>
                    <li class="show-onMini active"><a id="trigger-top" href="#">Inn</a></li>
                    <li class="show-onLarge"><a href="#">Rules &amp; ToS</a></li>
                    <li class="show-onMedium"><a href="#">Chat <span style="color:lime;">33</span></a></li>
		            <li><a href="#">Media</a></li>
                    <li><a href="#">Community</a></li>
                </ul>
                <ul class="nav nav-tabs" id="trigger-tabs">
                    <li id="trigger-right">
                        <div class="circle circle-small">
                            <i class="fa fa-user"></i>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tab menu -->
        <?php if(!empty($this->tabbar)) { ?>
        <div id="tabbar">
            <?=$this->tabbar?>
        </div>
        <?php } ?>

        <!-- Content -->
        <?php # Decide the #content class.
            if(empty($this->leftSide) && empty($this->rightSide)) {
                $cClass = "cType1";
            } elseif(
                empty($this->leftSide) && !empty($this->rightSide)
                || !empty($this->leftSide) && empty($this->rightSide)
            ) {
                $cClass = "cType2";
            } elseif(!empty($this->leftSide) && !empty($this->rightSide)) {
                $cClass = "cType3";
            }
            if(empty($this->tabbar)) $tClass = "extraMargin";
            else                     $tClass = "no-extraMargin";
        ?>
        <div id="outerContent" class="<?=$tClass?>">
            <?php if(!empty($this->leftSide)) { ?>
            <div id="leftSide">
                <?=$this->leftSide?>
            </div>
            <?php } ?>
            <div id="content" class="<?=$cClass?> white-box">
                <?=$content?>
            </div>
            <?php if(!empty($this->rightSide)) { ?>
            <div id="rightSide">
                <?=$this->rightSide?>
            </div>
            <?php } ?>
        </div>

        <!-- Copyright and the like. -->
        <div id="footer">
        </div>
    </body>
</html>
