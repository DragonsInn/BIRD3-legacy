<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="ie6"> <![endif]-->
<!--[if IE 7]>         <html class="ie7"> <![endif]-->
<!--[if IE 8]>         <html class="ie8"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="pure"><!--<![endif]-->
    <head>
        <?php // PHP logic
            $meta_desc = "The Dragon's Inn is a cozy place for furry and non-furry"
                ." roleplayers as well as casual chatters."
                ." Stop by and hang out with artists and freaks! =)";
            $pageTitle = "MYNAME".": Derp";#.$this->pageTitle;
            $cdn = BIRD3\Support\GlobalConfig::get("CDN.baseUrl");
            $img = "$cdn/images/favicons";
            $hash = Hprose::get("wpHash");
        ?>

        <title><?=$pageTitle?></title>
        <meta charset="utf-8"/>

        <!-- casual -->
        <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1"/>
        <meta name="description" content="<?=$meta_desc?>"/>
        <!-- Facebook -->
        <meta property="og:title" content="<?=$pageTitle?>"/>
        <meta property="og:type" content="<?=$this->og_type?>"/>
        <meta property="og:image" content="<?=$this->og_image?>"/>
        <meta property="og:url" content="<?=Request::url()?>"/>
        <meta property="og:description" content="<?=$meta_desc?>"/>
        <meta property="fb:admins" content="SexyXynu"/>
        <!-- Twitter -->
        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:url" content="<?=Request::url()?>"/>
        <meta name="twitter:title" content="<?=$pageTitle?>"/>
        <meta name="twitter:description" content="<?=$meta_desc?>"/>
        <meta name="twitter:image" content="<?=$this->og_image?>"/>
        <!-- Favicon and icons -->
        <link rel="apple-touch-icon" sizes="57x57" href="<?=$img?>/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?=$img?>/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?=$img?>/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?=$img?>/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?=$img?>/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?=$img?>/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?=$img?>/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?=$img?>/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?=$img?>/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="<?=$img?>/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?=$img?>/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="<?=$img?>/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?=$img?>/favicon-16x16.png">
        <!--<link rel="manifest" href="/manifest.json">-->
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?=$img?>/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- Scripts -->
        <?=$this->makeTopScripts()?>
        <script type="text/javascript">
            // Google Analytics
            (function(i,s,o,g,r,a,m){
                i['GoogleAnalyticsObject']=r;
                i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)
                }, i[r].l=1*new Date();
                a=s.createElement(o), m=s.getElementsByTagName(o)[0];
                a.async=1;
                a.src=g;
                m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-58656116-1', 'auto');
            ga('send', 'pageview');
        </script>
    </head>
    <body class="panel-pusher">
        <div id="bg"></div>
        <div id="blurr-bg" class="<?=($this->isIndex ? "onIndex":$this->bg_class)?>">
        </div>
        <!--<div id="app">-->
            <div id="Panels">
                <!-- Panels -->
                <div id="Ptop" class="panel top">
                </div>
                <div id="Pleft" class="panel side left container">
                    <p>This search will look for Characters, Media and Forum entries.</p>
                    <div><input type="text" id="allSearch" class="form-control white-box"></div>
                </div>
                <div id="Pright" class="panel side right">
                    <?=Widget::UserSidebar()?>
                </div>
                <?php if(isset($this->panelBottom)): ?>
                <div id="Pbottom" class="panel bottom">
                    <?=$this->panelBottom?>
                </div>
                <?php endif; ?>
            </div>
            <div id="MainPage">
                <div id="TopSection">
                    <!-- Banner: Not on front-page and chat, but everywhere else. -->
                    <?php if(!$this->allPage && !$this->isIndex): ?>
                    <div id="banner"></div>
                    <?php endif; ?>

                    <!-- Menu, bottom part -->
                    <nav class="navbar navbar-soft">
                        <div class="container-fluid">
                            <div class="navbar-header">
                                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#BIRD3mainBar">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="#Pleft" title="Search" aria-label="Search" id="trigger-left">
                                    <i class="fa fa-search"></i>&nbsp;
                                </a>
                                <a class="navbar-brand" href="/">
                                    <?=HTML::image(
                                        "$cdn/images/di_icon.png",
                                        "Dragon's Inn icon",
                                        ["height"=>50, "style"=>"margin-top:-15px;margin-right:-10px;"]
                                    )?>
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
                                                "aria-expanded"=>"false",
                                                "aria-label"=>"$name dropdown",
                                                "role"=>"button"
                                            )
                                            : array()
                                        );
                                        $aria = ($isDropdown?'<span class="sr-only">dropdown</span> ':'');
                                        if(isset($data["icon"]))
                                            $icon = '<i class="visible-lg-inline-block '.$data["icon"].'"></i> ';
                                        else
                                            $icon = '';
                                        $link = HTML::link(
                                            $data["href"],
                                            /*$icon.*/$name/*.($isDropdown ? ' <i class="fa fa-caret-down"></i>':"")*/,
                                            $htmlops
                                        );

                                        echo "{$elem}{$link}\n";

                                        if($isDropdown) {
                                            echo '<ul class="dropdown-menu" role="menu">'."\n";
                                            foreach($data["entries"] as $info) {
                                                echo '<li>'.HTML::link(
                                                    $info["url"],
                                                    //'<span class="iconblock"><i class="'.$info["icon"].'"></i></span> '.
                                                    $info[0],
                                                    [
                                                        "aria-label"=>"{$info[0]}, menu entry"
                                                    ]
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
                                            Auth::check() // is user logged in?
                                            ? Auth::user()->username
                                            : "Login/Register"
                                        )?> <i class="fa fa-caret-right"></i>
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>

                    <!-- Emergency errors, Usually browsers. -->
                    <div id="browser_error">
                    </div>

                    <!-- Intro -->
                    <?php if($this->isIndex): ?>
                        <div class="container-fluid" id="intro">
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <?=HTML::image(
                                        "$cdn/theme/images/sign.png",
                                        "The Dragon's Inn logo",
                                        [
                                            "class"=>"center-block",
                                            "style"=>"height:350px;"
                                        ]
                                    )?>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <p class="lead">The Dragon's Inn</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Content -->
                <?php # Decide the #content class.
                    if(empty($this->leftSide) && empty($this->rightSide)) {
                        $cClass = "cType1";
                        $tClass = "oType1";
                    } elseif(
                        empty($this->leftSide) && !empty($this->rightSide)
                        || !empty($this->leftSide) && empty($this->rightSide)
                    ) {
                        $cClass = "cType2";
                        $tClass = "oType2";
                    } elseif(!empty($this->leftSide) && !empty($this->rightSide)) {
                        $cClass = "cType3";
                        $tClass = "oType3";
                    }
                    if($this->allPage) {
                        $acClass = "AllYourPageAreBelongToUs";
                        $tClass = "";
                    } else {
                        $acClass = "";
                    }/*else { // TODO: Tabs.
                        if(empty($this->tabbar)) {
                            $acClass = "normalPage";
                        } else {
                            $acClass = "normalPage-tabbed";
                        }
                    }*/
                ?>
                <div id="outerContent">
                    <!-- Tab menu -->
                    <?php if(!empty($this->tabbar)): ?>
                        <div id="tabbar">
                            <?=$this->tabbar?>
                        </div>
                    <?php endif; ?>
                    <div id="content" class="container-fluid <?=$acClass?>">
                        <!-- FIXME: HAX HAX HAX...hackfleisch. I need classes here. This is UGLY! -->
                        <div style="position:absolute;top:0;left:0;height:100%;width:100%;z-index:-1;" id="fogger"></div>
                        <div style="position:relative;height:100%;width:100%;">
                            <?=$content?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <?php if(!$this->allPage): ?>
            <!-- Copyright and the like. -->
            <div class="clearfix" id="elementToExpand"></div>
            <footer id="footer" class="container-fluid">
                <div class="col-md-5">
                    <div>Dragon's Inn, BIRD3 and design "Exciting Night" by <a href="http://ingwie.me">Ingwie Phoenix</a></div>
                    <div>Background artwork "Sa'Eti by night" by <a href="#">Max Killion</a></div>
                    <div>Contributed content is &copy; to their respective owners. See <?=HTML::link("/docs/Infos_and_credits","Credits")?></div>
                </div>
                <div class="col-md-2">
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="5N3GJGG42QJ2G">
                        <button type="submit" class="btn btn-default btn-sm" name="submit">
                            <div>Buy me a <i class="fa fa-beer"></i> beer and say thanks!</div>
                            <div>Donate via <i class="fa fa-paypal"></i> Paypal</div>
                        </button>
                    </form>
                </div>
                <div class="col-md-5">
                    <div>Version: <i><?=BIRD3::version()?></i></div>
                    <div>
                        On: <i><?=BIRD3::userBrowser()?></i>
                    </div>
                    <div>
                        <ul class="list-inline">
                            <li><?=HTML::link("/home/staff","Staff")?></li>
                            <li><?=HTML::mailto("staff@dragonsinn.tk","Contact")?></li>
                            <li><?=HTML::link("/docs/Infos_and_credits","Credits")?></li>
                            <li><button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#report">
                                Report issue
                            </button></li>
                        </ul>
                    </div>
                </div>
            </footer>
            <?php endif; ?>
        <!--</div>-->

        <!-- Modals -->
        <?php if(!$this->allPage): ?>
            <div class="modal bootstrap-dialog type-danger fade"
                 id="report" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form name="report_issue">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <p class="modal-title" id="myModalLabel">Report an issue.</p>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <p>
                                        You can use this window to report an issue to the Dragon's Inn staff.
                                    </p>
                                    <p>
                                        If you only seek to contact us, use the "Contact" link.
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label for="report_type">What would you like to report?</label>
                                    <select name="report_type" class="form-control" id="report_type">
                                        <option>A (staff-)member misbehaved.</option>
                                        <option>My art/character/material was posted here without permission.</option>
                                        <option>I have a technical problem with this site.</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="report_content">Describe the issue.</label>
                                    <textarea id="report_content" name="report_content" class="form-control" rows=10></textarea>
                                    <p class="help-block">
                                        Please provide any reference concerning the issue.
                                    </p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div id="ri_status" style="visibility:none;"></div>
                                <button type="button" class="btn btn-default">Send report.</button>
                                <p>This is currently disabled.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?=$this->makeBottomScripts()?>
    </body>
</html>
