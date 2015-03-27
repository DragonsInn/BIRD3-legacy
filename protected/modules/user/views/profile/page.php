<h2><?=$user->username?> <small>Owner, Administrator</small></h2>

<div class="well">
    <div class="row">
        <div class="col-md-2">
            <?=CHtml::image(User::avatarUrl($user->id), "Image", ["class"=>"img-thumbnail"])?>
        </div>
        <div class="col-md-5 ">
            <dl class="dl-horizontal">
                <dt><i class="fa fa-at"></i> E-Mail</dt>
                <dd><?=$user->email?></dd>
                <?php foreach($profile->attributeLabels() as $attr=>$disp): ?>
                <dt><?=$disp?></dt>
                <dd><?=$profile->getAttribute($attr)?></dd>
                <?php endforeach; ?>
            </dl>
        </div>
        <div class="col-md-5">
            <h4>About</h4>
            <?=$profile->about?>
        </div>
    </div>
</div>

<h3>Characters</h3>
<?php $col = "col-sm-4"; ?>
<div class="row">
    <div class="<?=$col?> col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-xs-4 col-sm-6">
                    <a href="#">
                        <?=CHtml::image(User::avatarUrl($user->id), "Image", ["class"=>"img-thumbnail"])?>
                    </a>
                </div>
                <div class="col-xs-8 col-sm-6">
                    <div><b>Name:</b> Xynu Shinizuki</div>
                    <div><b>Species:</b> Dragoness</div>
                    <div><b>Gender:</b> Female</div>
                    <div><b>Content:</b> Adult</div>
                    <div><b>Last played:</b> Yestarday</div>
                    <div><b>Status:</b> Main</div>
                </div>
            </div>
        </div>
    </div>
    <div class="<?=$col?> col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-xs-4 col-sm-6">
                    <a href="#">
                        <?=CHtml::image(User::avatarUrl($user->id), "Image", ["class"=>"img-thumbnail"])?>
                    </a>
                </div>
                <div class="col-xs-8 col-sm-6">
                    <div><b>Name:</b> Xynu Shinizuki</div>
                    <div><b>Species:</b> Dragoness</div>
                    <div><b>Gender:</b> Female</div>
                    <div><b>Content:</b> Adult</div>
                    <div><b>Last played:</b> Yestarday</div>
                    <div><b>Status:</b> Main</div>
                </div>
            </div>
        </div>
    </div>
    <div class="<?=$col?> col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-xs-4 col-sm-6">
                    <a href="#">
                        <?=CHtml::image(User::avatarUrl($user->id), "Image", ["class"=>"img-thumbnail"])?>
                    </a>
                </div>
                <div class="col-xs-8 col-sm-6">
                    <div><b>Name:</b> Xynu Shinizuki</div>
                    <div><b>Species:</b> Dragoness</div>
                    <div><b>Gender:</b> Female</div>
                    <div><b>Content:</b> Adult</div>
                    <div><b>Last played:</b> Yestarday</div>
                    <div><b>Status:</b> Main</div>
                </div>
            </div>
        </div>
    </div>
    <div class="<?=$col?> col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-xs-4 col-sm-6">
                    <a href="#">
                        <?=CHtml::image(User::avatarUrl($user->id), "Image", ["class"=>"img-thumbnail"])?>
                    </a>
                </div>
                <div class="col-xs-8 col-sm-6">
                    <div><b>Name:</b> Xynu Shinizuki</div>
                    <div><b>Species:</b> Dragoness</div>
                    <div><b>Gender:</b> Female</div>
                    <div><b>Content:</b> Adult</div>
                    <div><b>Last played:</b> Yestarday</div>
                    <div><b>Status:</b> Main</div>
                </div>
            </div>
        </div>
    </div>
    <div class="<?=$col?> col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-xs-4 col-sm-6">
                    <a href="#">
                        <?=CHtml::image(User::avatarUrl($user->id), "Image", ["class"=>"img-thumbnail"])?>
                    </a>
                </div>
                <div class="col-xs-8 col-sm-6">
                    <div><b>Name:</b> Xynu Shinizuki</div>
                    <div><b>Species:</b> Dragoness</div>
                    <div><b>Gender:</b> Female</div>
                    <div><b>Content:</b> Adult</div>
                    <div><b>Last played:</b> Yestarday</div>
                    <div><b>Status:</b> Main</div>
                </div>
            </div>
        </div>
    </div>
    <div class="<?=$col?> col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-xs-4 col-sm-6">
                    <a href="#">
                        <?=CHtml::image(User::avatarUrl($user->id), "Image", ["class"=>"img-thumbnail"])?>
                    </a>
                </div>
                <div class="col-xs-8 col-sm-6">
                    <div><b>Name:</b> Xynu Shinizuki</div>
                    <div><b>Species:</b> Dragoness</div>
                    <div><b>Gender:</b> Female</div>
                    <div><b>Content:</b> Adult</div>
                    <div><b>Last played:</b> Yestarday</div>
                    <div><b>Status:</b> Main</div>
                </div>
            </div>
        </div>
    </div>
    <div class="<?=$col?> col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-xs-4 col-sm-6">
                    <a href="#">
                        <?=CHtml::image(User::avatarUrl($user->id), "Image", ["class"=>"img-thumbnail"])?>
                    </a>
                </div>
                <div class="col-xs-8 col-sm-6">
                    <div><b>Name:</b> Xynu Shinizuki</div>
                    <div><b>Species:</b> Dragoness</div>
                    <div><b>Gender:</b> Female</div>
                    <div><b>Content:</b> Adult</div>
                    <div><b>Last played:</b> Yestarday</div>
                    <div><b>Status:</b> Main</div>
                </div>
            </div>
        </div>
    </div>
    <div class="<?=$col?> col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-xs-4 col-sm-6">
                    <a href="#">
                        <?=CHtml::image(User::avatarUrl($user->id), "Image", ["class"=>"img-thumbnail"])?>
                    </a>
                </div>
                <div class="col-xs-8 col-sm-6">
                    <div><b>Name:</b> Xynu Shinizuki</div>
                    <div><b>Species:</b> Dragoness</div>
                    <div><b>Gender:</b> Female</div>
                    <div><b>Content:</b> Adult</div>
                    <div><b>Last played:</b> Yestarday</div>
                    <div><b>Status:</b> Main</div>
                </div>
            </div>
        </div>
    </div>
    <div class="<?=$col?> col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-xs-4 col-sm-6">
                    <a href="#">
                        <?=CHtml::image(User::avatarUrl($user->id), "Image", ["class"=>"img-thumbnail"])?>
                    </a>
                </div>
                <div class="col-xs-8 col-sm-6">
                    <div><b>Name:</b> Xynu Shinizuki</div>
                    <div><b>Species:</b> Dragoness</div>
                    <div><b>Gender:</b> Female</div>
                    <div><b>Content:</b> Adult</div>
                    <div><b>Last played:</b> Yestarday</div>
                    <div><b>Status:</b> Main</div>
                </div>
            </div>
        </div>
    </div>
    <div class="<?=$col?> col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-xs-4 col-sm-6">
                    <a href="#">
                        <?=CHtml::image(User::avatarUrl($user->id), "Image", ["class"=>"img-thumbnail"])?>
                    </a>
                </div>
                <div class="col-xs-8 col-sm-6">
                    <div><b>Name:</b> Xynu Shinizuki</div>
                    <div><b>Species:</b> Dragoness</div>
                    <div><b>Gender:</b> Female</div>
                    <div><b>Content:</b> Adult</div>
                    <div><b>Last played:</b> Yestarday</div>
                    <div><b>Status:</b> Main</div>
                </div>
            </div>
        </div>
    </div>
</div>
