<!DOCTYPE html>
<html>
    <head>
        <?php // PHP logic
            $meta_desc = "The Dragon's Inn is a cozy place for furry and non-furry"
                ." roleplayers as well as casual chatters."
                ." Stop by and hang out with artists and freaks! =)";
            $pageTitle = Yii::app()->name.": ".$this->pageTitle;
            $this->registerScripts();
            $tbase = Yii::app()->theme->baseUrl;
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
            var useBottomPanel = false;
            <?php if($this->panelBottom != false) { ?>
                useBottomPanel = true;
            <?php } ?>
        </script>

        <!-- The theme -->
        <link rel="stylesheet" type="text/css" href="<?=$tbase.'/css/main.ws.php'?>" />
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
                BIRD3@<?=Yii::app()->params['version']?>
            </div>
        </div>
        <?php if($this->panelBottom != false) { ?>
        <div id="Pbottom" class="panel-default panel-bottom">
            <?=$this->panelBottom?>
        </div>
        <?php } ?>

        <!-- Menu, bottom part -->
        <div id="menu">
            <div class="left">
                <div style="float:left;" id="trigger-left">
                    <img
                        src="<?=$tbase?>/images/icons/search.png"
                        class="icon"
                        aria-hidden="true"
                    />
                </div>
                <div class="text-left">
                    <a href="#">o.o</a>
                </div>
            </div>
            <div class="center">
                <div id="trigger-top">
                    <img
                        src="<?=$tbase?>/images/icons/download.png"
                        class="icon"
                        aria-hidden="true"
                    />
                </div>
            </div>
            <div class="right">
                <div class="text-right">
                    B DIV
                </div>
                <div style="float:right;" id="trigger-right">
                    <img
                        src="<?=$tbase?>/images/icons/pacman-games.png"
                        class="icon"
                        aria-hidden="true"
                    />
                </div>
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
