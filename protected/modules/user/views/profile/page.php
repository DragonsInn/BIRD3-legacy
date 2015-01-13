<h2><?=$user->username?> <small>Owner, Administrator</small></h2>

<div class="row">
    <div class="col-md-2">
        <img class="img-thumbnail" src="<?=$this->createUrl("/user/profile/avvie", ["id"=>Yii::app()->user->id])?>">
    </div>
    <div class="col-md-5 well">
        <dl class="dl-horizontal">
            <?php foreach($profile->attributeLabels() as $attr=>$disp): ?>
            <dt><?=$disp?></dt>
            <dd><?=$profile->getAttribute($attr)?></dd>
            <?php endforeach; ?>
        </dl>
    </div>
    <div class="col-md-5 well">
        <h4>About</h4>
        <?=$profile->about?>
    </div>
</div>

<h3>Characters</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Species</th>
            <th>Gender</th>
            <th>Last palyed</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Mo</td>
            <td>Dragon/Wolviex</td>
            <td>Male</td>
            <td>1st December 2014</td>
            <td>Main</td>
        </tr>
    </tbody>
</table>
