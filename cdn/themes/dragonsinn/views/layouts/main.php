<!DOCTYPE html>
<html>
    <head>
        <?php // PHP logic
            $meta_desc = "The Dragon's Inn is a cozy place for furry and non-furry"
                ." roleplayers as well as casual chatters."
                ." Stop by and hang out with artists and freaks! =)";
            $cs = Yii::app()->clientScript;
            $theme = Yii::app()->theme;
            $tbase = $theme->getBaseUrl();
            $cs->registerCssFile($tbase.'/css/main.ws.php');
            $cs->registerScriptFile($tbase."/js/panels.js", CClientScript::POS_END);
            $cs->registerScriptFile(Yii::app()->cdn->getBaseUrl()."/bootstrap/js/bootstrap.min.js", CClientScript::POS_END);
            $pageTitle = Yii::app()->name.": ".$this->pageTitle;
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
            <?php if($this->panelBottom != false) { ?>
                var useBottomPanel = true;
            <?php } else { ?>
                var useBottomPanel = false;
            <?php } ?>
        </script>
    </head>
    <body class="panel-pusher">
        <!-- Panels -->
        <div id="Ptop" class="panel-default panel-top">
        </div>
        <div id="Pleft" class="panel-default panel-side panel-left">
            <div>
                <input type="search" name="search" class="form-control white-box" placeholder="Search/Command..."/>
            </div>
        </div>
        <div id="Pright" class="panel-default panel-side panel-right">
            <div id="login">
                <h3>Login</h3>
                <div class="input-group">
                    <input type="text" placeholder="Username" name="userName" required class="form-control" />
                </div>
                <div class="input-group">
                    <input type="password" class="form-control" placeholder="Password" required name="password"/>
                    <span class="input-group-btn">
                        <button class="btn btn-inverse" type="submit">Go!</button>
                    </span>
                </div>
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
                    <img src="<?=$tbase?>/images/icons/search.png" class="icon"/>
                </div>
                <div class="text-left">
                    <a href="#">o.o</a>
                </div>
            </div>
            <div class="center">
                <div id="trigger-top">
                    <img src="<?=$tbase?>/images/icons/download.png" class="icon"/>
                </div>
            </div>
            <div class="right">
                <div class="text-right">
                    B DIV
                </div>
                <div style="float:right;" id="trigger-right">
                    <img src="<?=$tbase?>/images/icons/pacman-games.png" class="icon"/>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div id="outerContent">
            <div id="content" class="white-box">
                <?=$content?>
            </div>
        </div>

        <!-- Copyright and the like. -->
        <div id="footer">
        </div>
    </body>
</html>
