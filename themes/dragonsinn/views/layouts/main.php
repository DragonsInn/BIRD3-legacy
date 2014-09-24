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
                <?php function makeBubbles(array $links) { foreach($links as $id=>$obj) { ?>
                <div id="<?=$id?>">
                    <?php foreach($obj as $title=>$data) { ?>
                    <a class="linkBubble" href="<?=CHtml::normalizeUrl($data["url"])?>">
                        <div class="circle circle-icon">
                            <i class="<?=$data["icon"]?>"></i>
                        </div>
                        <div style="text-align:center;" class="tsection">
                            <?=$title?>
                        </div>
                    </a>
                    <?php } ?>
                </div>
                <?php } } ?>
                <?php makeBubbles(array(
                    "TheInn"=>array(
                        "Home"=>array("icon"=>"fa fa-home", "url"=>array("/")),
                        "Staff"=>array("icon"=>"glyphicon glyphicon-certificate", "url"=>array("/home/staff")),
                        "Infos/Credits"=>array("icon"=>"fa fa-exclamation", "url"=>array("/home/infos")),
                        "Manage"=>array("icon"=>"fa fa-cogs","url"=>array("/home/manage"))
                    ),
                    "Hotel"=>array(
                        "Story"=>array("icon"=>"fa fa-file-text","url"=>array("/hotel/story")),
                        "Places"=>array("icon"=>"fa fa-compass","url"=>array("/hotel/places")),
                        "Jobs"=>array("icon"=>"fa fa-building","url"=>array("/hotel/jobs"))
                    ),
                    "Characters"=>array(
                        "Latest"=>array("icon"=>"fa fa-list","url"=>array("/chars/latest")),
                        "All"=>array("icon"=>"fa fa-database","url"=>array("/chars/all")),
                        "Fams/Clans"=>array("icon"=>"fa fa-child","url"=>array("/chars/associations")),
                        "Jobs"=>array("icon"=>"fa fa-building","url"=>array("/chars/jobs"))
                    ),
                    "Media"=>array(
                        "Latest"=>array("icon"=>"fa fa-list","url"=>array("/media/all/latest")),
                        "All"=>array("icon"=>"fa fa-folder","url"=>array("/media/all/list")),
                        "Art"=>array("icon"=>"fa fa-paint-brush","url"=>array("/media/art")),
                        "Music"=>array("icon"=>"glyphicon glyphicon-headphones","url"=>array("/media/audio")),
                        "Essay"=>array("icon"=>"glyphicon glyphicon-bookmark","url"=>array("/media/story"))
                    ),
                    "Community"=>array(
                        "Users"=>array("icon"=>"fa fa-users","url"=>array("/user/list")),
                        "Forum"=>array("icon"=>"fa fa-comment","url"=>array("/form")),
                        "Blogs"=>array("icon"=>"glyphicon glyphicon-list-alt","url"=>array("/blog"))
                    ),
                )); ?>
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
                                <form name="ContentCreate" class="form-inline">
                                    <div class="form-group">
                                        <select class="form-control">
                                            <option>Character</option>
                                            <option>Art</option>
                                            <option>Music</option>
                                            <option>Essay</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="m-btn blue icn-only">
                                        <i class="m-icon-swapright m-icon-white"></i>
                                    </button>
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
