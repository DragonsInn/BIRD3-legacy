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
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
        <!-- Tiny bit of JS -->
        <script>
            var useBottomPanel = <?=($this->panelBottom?"true":"false")?>;
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-58656116-1', 'auto');
            ga('send', 'pageview');
        </script>
    </head>
    <body class="panel-pusher">
        <div id="TopSection">
            <!-- Panels -->
            <div id="Ptop" class="panel-default panel-top">
            </div>
            <div id="Pleft" class="panel-default panel-side panel-left container">
                <p>Search not implemented yet.</p>
            </div>
            <div id="Pright" class="panel-default panel-side panel-right">
                <?php
                    if(Yii::app()->user->isGuest)
                        $this->widget("BIRD3LoginWidget");
                    else
                        $this->widget("BIRD3SidebarWidget");
                ?>
            </div>
            <?php if($this->panelBottom != false) { ?>
            <div id="Pbottom" class="panel-default panel-bottom">
                <?=$this->panelBottom?>
            </div>
            <?php } ?>

            <!-- Menu, bottom part -->
            <nav class="navbar navbar-soft">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button class="navbar-toggle collapsed"
                            data-toggle="collapse" data-target="#BIRD3mainBar"
                        >
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#Pleft" title="Search" aria-label="Search" id="trigger-left">
                            <i class="fa fa-search"></i>&nbsp;
                        </a>
                        <a class="navbar-brand" href="/">
                            <img title="Dragon's Inn icon" alt="Dragon's Inn icon"
                                src="/cdn/images/di_icon.png"
                                height="50" style="margin-top:-15px;">
                        </a>
                    </div>

                    <!-- navs -->
                    <div class="collapse navbar-collapse" id="BIRD3mainBar">
                        <ul class="nav navbar-nav">
                            <?php
                            foreach($this->navEntries as $name=>$data) {
                                $elem = "";
                                $hash = md5($name);
                                $dropdown = "";
                                $isDropdown = (isset($data["entries"]) && !empty($data["entries"]));
                                if($isDropdown)
                                    $elem = '<li class="dropdown">';
                                else
                                    $elem = '<li>';

                                // Generate the link tag
                                $htmlops = ( $isDropdown
                                    ? array(
                                        "class"=>"dropdown-toggle",
                                        "data-toggle"=>"dropdown",
                                        "role"=>"button",
                                        "aria-expanded"=>"false"
                                    )
                                    : array()
                                );
                                if(isset($data["icon"]))
                                    $icon = '<i class="visible-lg-inline-block '.$data["icon"].'"></i> ';
                                else
                                    $icon = '';
                                $link = CHtml::link(
                                    $icon.$name.($isDropdown ? ' <i class="fa fa-caret-down"></i>':""),
                                    $data["href"], $htmlops
                                );

                                echo "{$elem}{$link}\n";

                                if($isDropdown) {
                                    echo '<ul class="dropdown-menu" role="menu">'."\n";
                                    foreach($data["entries"] as $info) {
                                        echo '<li>'.CHtml::link(
                                            '<span class="iconblock"><i class="'.$info["icon"].'"></i></span> '.$info[0],
                                            $info["url"]
                                        ).'</li>';
                                    }
                                    echo '</ul>';
                                }
                                echo "</li>\n";
                            }
                            ?>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="#Pright" id="trigger-right">
                                <i class="fa fa-user"></i> <?=(
                                    Yii::app()->user->isGuest
                                    ? "Login/Register"
                                    : Yii::app()->user->username
                                )?> <i class="fa fa-caret-right"></i>
                            </a></li>
                        </ul>
                    </div>
                </div>
            </nav>
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
            if($this->allPage)       $acClass = "AllYourPageAreBelongToUs";
            else                     $acClass = "";
        ?>
        <div id="outerContent" class="<?=$tClass?> <?=$acClass?>">
            <?php if(!empty($this->leftSide)) { ?>
            <div id="leftSide">
                <?=$this->leftSide?>
            </div>
            <?php } ?>
            <div id="content" class="<?=$cClass?> white-box container-fluid">
                <?=$content?>
            </div>
            <?php if(!empty($this->rightSide)) { ?>
            <div id="rightSide">
                <?=$this->rightSide?>
            </div>
            <?php } ?>
        </div>
        <div class="clearfix"></div>
        <!-- Copyright and the like. -->
        <div id="footer" class="container-fluid">
            <div class="col-md-5">
                <div>Dragon's Inn was created using BIRD3. Both by Ingwie Phoenix</div>
                <div>Background by <a href="#" style="background:black;">Max Killion</a></div>
                <div>Design "Exciting Night" by Ingwie Phoenix</div>
            </div>
            <div class="col-md-2">
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="5N3GJGG42QJ2G">
                    <button type="submit" class="btn btn-primary" name="submit">
                        <div>Buy me a <i class="fa fa-beer"></i> beer and say thanks!</div>
                        <div>Donate via <i class="fa fa-paypal"></i> Paypal</div>
                    </button>
                </form>
            </div>
            <div class="col-md-5">
                <div>Version: <i>BIRD@<?=Yii::app()->params['version']?></i></div>
                <div>Staff | Contact | Credits</div>
            </div>
        </div>
    </body>
</html>
