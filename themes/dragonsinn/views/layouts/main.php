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
        <div id="TopSection">
            <!-- Panels -->
            <div id="Ptop" class="panel-default panel-top">
            </div>
            <div id="Pleft" class="panel-default panel-side panel-left container">
                <p>Search not implemented yet.</p>
            </div>
            <div id="Pright" class="panel-default panel-side panel-right">
                <?php if(Yii::app()->user->isGuest) $this->widget("BIRD3LoginWidget"); else { ?>
                    <div>
                        <?php $user = Yii::app()->user; ?>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span class="badge"><?=$user->username?></span>
                                <p>You</p>
                                <div class="btn-group btn-group-xs">
                                    <button type="button" class="btn btn-default">Profile</button>
                                    <button type="button" class="btn btn-default">Settings</button>
                                    <button type="button" class="btn btn-danger">Logout</button>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <span class="badge alert-warning">On</span>
                                Developer Mode
                            </li>
                            <li class="list-group-item">
                                <span class="badge alert-info">14</span>
                                <p>Private Messages</p>
                                <div class="btn-group btn-group-xs">
                                    <button type="button" class="btn btn-info">Compose</button>
                                    <button type="button" class="btn btn-info">Inbox</button>
                                    <button type="button" class="btn btn-info">Outbox</button>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <span class="badge alert-success">14</span>
                                Characters
                            </li>
                            <li class="list-group-item">
                                <span class="badge alert-success">14</span>
                                Art
                            </li>
                            <li class="list-group-item">
                                <span class="badge alert-success">14</span>
                                Music
                            </li>
                            <li class="list-group-item">
                                <span class="badge alert-success">14</span>
                                Essays
                            </li>
                        </ul>
                        <hr>
                        <div class="panel panel-primary">
                            <div class="panel-heading">Create / Upload</div>
                            <div class="panel-body">
                                <form name="ContentCreate">
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <select class="form-control">
                                                <option>Character</option>
                                                <option>Art</option>
                                                <option>Music</option>
                                                <option>Essay</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-4">
                                            <button type="submit" class="m-btn blue icn-only">
                                                <i class="m-icon-swapright m-icon-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
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
            <nav class="navbar navbar-soft">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button
                            type="button" class="navbar-toggle collapsed"
                            data-toggle="collapse" data-target="#BIRD3mainBar"
                        >
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#" id="trigger-left">
                            <i class="fa fa-search"></i>&nbsp;
                        </a>
                        <a class="navbar-brand" href="/">
                            <img src="/cdn/images/di_icon.png" height=50 style="margin-top:-15px;">
                        </a>
                    </div>

                    <!-- navs -->
                    <div class="collapse navbar-collapse" id="BIRD3mainBar">
                        <ul class="nav navbar-nav">
                            <?php
                            $lis = array(
                                "Dragon's Inn"=>array(
                                    "href"=>"#",
                                    "entries"=>array(
                                        array("Home", "icon"=>"fa fa-home", "url"=>array("/")),
                                        array("<font color=\"red\">Rules/TOS</font>", "icon"=>"fa fa-legal", "url"=>array("/docs/rules")),
                                        array("Staff", "icon"=>"glyphicon glyphicon-certificate", "url"=>array("/home/staff")),
                                        array("Infos/Credits", "icon"=>"fa fa-exclamation", "url"=>array("/home/infos")),
                                        array("Manage", "icon"=>"fa fa-cogs","url"=>array("/home/manage"))
                                    )
                                ),
                                "Chat <font color=lime>NN</font>"=>array(
                                    "href"=>array("/chat"),
                                    "icon"=>"fa fa-comments"
                                ),
                                "Hotel"=>array(
                                    "href"=>"#",
                                    "icon"=>"fa fa-globe",
                                    "entries"=>array(
                                        array("Story", "icon"=>"fa fa-file-text","url"=>array("/hotel/story")),
                                        array("Places", "icon"=>"fa fa-compass","url"=>array("/hotel/places")),
                                        array("Jobs", "icon"=>"fa fa-building","url"=>array("/hotel/jobs"))
                                    ),
                                ),
                                "Characters"=>array(
                                    "href"=>"#",
                                    "icon"=>"fa fa-book",
                                    "entries"=>array(
                                        array("Latest", "icon"=>"fa fa-list","url"=>array("/chars/latest")),
                                        array("All", "icon"=>"fa fa-database","url"=>array("/chars/all")),
                                        array("Families &amp; Clans", "icon"=>"fa fa-child","url"=>array("/chars/associations")),
                                        array("Jobs", "icon"=>"fa fa-building","url"=>array("/chars/jobs"))
                                    ),
                                ),
                                "Media"=>array(
                                    "href"=>"#",
                                    "icon"=>"glyphicon glyphicon-eye-open",
                                    "entries"=>array(
                                        array("Latest", "icon"=>"fa fa-list","url"=>array("/media/all/latest")),
                                        array("All", "icon"=>"fa fa-folder","url"=>array("/media/all/list")),
                                        array("Art", "icon"=>"fa fa-paint-brush","url"=>array("/media/art")),
                                        array("Music", "icon"=>"glyphicon glyphicon-headphones","url"=>array("/media/audio")),
                                        array("Essay", "icon"=>"glyphicon glyphicon-bookmark","url"=>array("/media/story"))
                                    )
                                ),
                                "Community"=>array(
                                    "href"=>"#",
                                    "icon"=>"fa fa-users",
                                    "entries"=>array(
                                        array("Users", "icon"=>"fa fa-users","url"=>array("/user/list")),
                                        array("Forum", "icon"=>"fa fa-comment","url"=>array("/form")),
                                        array("Blogs", "icon"=>"glyphicon glyphicon-list-alt","url"=>array("/blog"))
                                    )
                                ),
                            );
                            foreach($lis as $name=>$data) {
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
                            <li><a href="#" id="trigger-right">
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
            <!--
            <div id="menu">
                <div class="tabbable tabs-below tabs-multi">
                    <ul class="nav nav-tabs">
                        <li id="trigger-left">
                            <div class="circle circle-small">
                                <i class="fa fa-search"></i>
                            </div>
                        </li>
                    </ul>
                    <?php $this->widget("BIRD3Menu", array(
                        "id"=>"BIRD3Menu",
                        "links"=>array(
                            array(
                                "class"=>"isTab", "url"=>"#TheInn",
                                "mini"=>'Inn', "medium"=>'Inn', "large"=>'The Inn',
                                "big"=>'<i class="fa fa-smile-o"></i> The Inn'
                            ),
                            array(
                                "url"=>array("/docs/rules"),
                                "mini"=>'ToS', "medium"=>'ToS', "large"=>'ToS',
                                "big"=>'<i class="fa fa-legal"></i> Rules &amp; ToS'
                            ),
                            array(
                                "url"=>"#Hotel", "class"=>"isTab",
                                "mini"=>'Hotel', "medium"=>'Hotel', "large"=>'Hotel',
                                "big"=>'<i class="fa fa-globe"></i> Hotel'
                            ),
                            array(
                                "url"=>array("/chat"),
                                "mini"=>'Chat',
                                "medium"=>'Chat <span style="color:lime;">NN</span>',
                                "large"=>'Chat <span style="color:lime;">NN</span>',
                                "big"=>'<i class="fa fa-comments"></i> Chat <span style="color:lime;">NN</span>'
                            ),
                            array(
                                "url"=>"#Characters", "class"=>"isTab",
                                "mini"=>'Chars', "medium"=>'Chars', "large"=>'Chars',
                                "big"=>'<i class="fa fa-book"></i> Characters'
                            ),
                            array(
                                "url"=>"#Media", "class"=>"isTab",
                                "mini"=>"Media", "medium"=>'Media', "large"=>'Media',
                                "big"=>'<i class="glyphicon glyphicon-eye-open"></i> Media'
                            ),
                            array(
                                "url"=>"#Community", "class"=>"isTab",
                                "mini"=>'We!', "medium"=>'Comm.', "large"=>'Community',
                                "big"=>'<i class="fa fa-users"></i> Community'
                            )
                        )
                    )); ?>
                    <ul class="nav nav-tabs" id="trigger-tabs">
                        <li id="trigger-right">
                            <div class="circle circle-small">
                                <i class="fa fa-user"></i>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            -->
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
            <div id="content" class="<?=$cClass?> white-box container">
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
            <div>Dragon's Inn was created using BIRD3. Both by Ingwie Phoenix</div>
            <div>Background by <a href="#" style="background:black;">Max Killion</a></div>
            <div>Design "Exciting Night" by Ingwie Phoenix</div>
            <div>Staff | Contact | Credits</div>
            <div>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="5N3GJGG42QJ2G">
                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Donate to Dragon's Inn!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            </div>
        </div>
    </body>
</html>
