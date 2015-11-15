<h2><?=$user->username?> <small>Owner, Administrator</small></h2>

<div class="well">
    <div class="row">
        <div class="col-md-2">
            <?php #HTML::image(User::avatarUrl($user->id), "Image", ["class"=>"img-thumbnail"])?>
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
            <!-- auto render serverside! -->
            <div data-bird3-markdown="true">
                <?=$profile->about?>
            </div>
        </div>
    </div>
</div>

<h3>Characters</h3>
<p>To be added...</p>
