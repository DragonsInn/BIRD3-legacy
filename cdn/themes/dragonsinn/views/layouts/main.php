<!DOCTYPE html>
<html>
    <head>
        <title><?=Yii::app()->name.": ".$this->pageTitle?></title>

        <?php // PHP logic
            $meta_desc = "The Dragon's Inn is a cozy place for furry and non-furry"
                ." roleplayers as well as casual chatters."
                ." Stop by and hang out with artists and freaks! =)";
            $cs = Yii::app()->clientScript;
            $theme = Yii::app()->theme;
            $cs->registerCssFile($theme->getBaseUrl().'/css/main.ws.php');
            $cs->registerScriptFile($theme->getBaseUrl()."/js/panels.js", CClientScript::POS_END);
        ?>

        <!-- casual -->
        <meta name="description" content="<?=$meta_desc?>"/>
        <!-- Facebook -->
        <meta property="og:title" content="Dragon's Inn"/>
        <meta property="og:type" content="<?=$this->og_type?>"/>
        <meta property="og:image" content="<?=$this->og_image?>"/>
        <meta property="og:url" content="<?=Yii::app()->request->url?>"/>
        <meta property="og:description" content="<?=$meta_desc?>"/>
        <meta property="fb:admins" content="SexyXynu"/>
        <!-- Twitter -->
        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:url" content="<?=Yii::app()->request->url?>"/>
        <meta name="twitter:title" content="<?=Yii::app()->name.": ".$this->pageTitle?>"/>
        <meta name="twitter:description" content="<?=$meta_desc?>"/>
        <meta name="twitter:image" content="<?=$this->og_image?>"/>
    </head>
    <body>
        <div id="Ptop" class="panel-default panel-top"></div>
        <div id="Pleft" class="panel-default panel-side panel-left"></div>
        <div id="Pright" class="panel-default panel-side panel-right"></div>

        <?php if($this->panelBottom != false) { ?>
        <div id="Pbottom" class="panel-default panel-bottom"></div>
        <?php } ?>

        <button id="trigger-top">TOP(man) panel</button>

        <div id="content">
            <?=$content?>
        </div>

        <div id="footer"></div>

        <script>
            /*bool*/ var useBottomPanel;
            <?php if($this->panelBottom != false) { ?>
                useBottomPanel=true;
            <?php } else { ?>
                useBottomPanel=false;
            <?php } ?>
        </script>
    </body>
</html>
