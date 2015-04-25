<?php
/* @var $this SiteController */

$this->pageTitle="Home";
#$this->allPage=true;
#$this->tabbar="WHADAFU1";
$this->isIndex=true;
?>

<h5>Friendly reminder: <small>This is a demo page.</small></h5>
<div><pre><?php
    /*BIRD3::mail([
        "to"=>"ingwie2000@gmail.com",
        "subject"=>"Testing nodemailer",
        "text"=>"o.o"
    ]);*/
    print_r($_COOKIE);
?></pre></div

<div class="row">
    <div class="col-md-12">
        <div class="well well-lg">
            <div><h5>Pseudo news</h5></div>
            <div><i>By <?=User::getHtml(1)?></i></div>
            <p>
                This is a pseudo news entry that you can imagine containing some super special contest
                announcement, for instance!
            </p>
        </div>
    </div>
</div>

<div class="sr-only">More news are below. <a href="#MoreNews">Click here to skip to them.</a></div>

<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="well well-sm"><h4>Recent art</h4></div>
                <div class="row">
                    <div class="col-sm-4 col-md-4">
                        <div class="thumbnail thumbnail-soft">
                            <?php #CHtml::image(User::avatarUrl(4), "Xynu")?>
                            <div class="caption">
                                <div class="text-center"><a href="#" style="color:white">Xynu</a></div>
                                <div class="text-center">Blindwatchman</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="well well-sm"><h4>Recent Characters</h4></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="well well-sm">
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <?php /*CHtml::image(User::avatarUrl(4), "Xynu", [
                                            "class"=>"media-object",
                                            "height"=>100
                                        ])*/ ?>
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h5 class="media-heading">Xynu Shinizuki</h5>
                                    <div><small>By: <font color="lime">Ingwie Phoenix</font></small></div>
                                    <div><small>Female, Dragoness</small></div>
                                    <p>Here follows a short summary of Xynu.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="well well-sm"><h4>Recent essays</h4></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="well well-sm">
                            <div class="media">
                                <div class="media-left">
                                    <div><font color="orange">Khellendros</font></div>
                                    <div>N pages</div>
                                    <div>N words</div>
                                </div>
                                <div class="media-body">
                                    <h5 class="media-heading">Shi'rans world</h5>
                                    <p>A bit of a summary.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="well well-sm"><h4>Recent music</h4></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="well well-lg">
                            <div class="well well-sm">
                                <div class="media">
                                    <div class="media-left">
                                        <a href="#">
                                            <?php /*CHtml::image(User::avatarUrl(4), "Xynu", [
                                                "class"=>"media-object",
                                                "height"=>100
                                            ])*/ ?>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="media-heading">Some cool tunes</h5>
                                        <div>By: <font color="lime">Jero</font></div>
                                        <div>Genre: Deep House</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" id="MoreNews">
    <div class="col-md-12">
        <div class="well well-lg">
            <div><h5>Pseudo news 2</h5></div>
            <div><i>By <font color="lime">Ingwie Phoenix</font></i></div>
            <p>
                You would probably see other important blog posts here. ...but yeah, demo.
            </p>
        </div>
    </div>
</div>
