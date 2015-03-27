<div>
    <?php $user = Yii::app()->user; ?>
    <ul class="list-group">
        <li class="list-group-item">
            <span class="pull-right"><?=CHtml::link(
                "Logout", ["/user/logout"],
                ["class"=>"btn btn-danger btn-xs"]
            )?></span>
            <p>Yourself</p>
            <div class="btn-group btn-group-xs">
                <?=CHtml::link(
                    "Settings", ["/user/settings"],
                    ["class"=>"btn btn-default"]
                )?>
                <?=CHtml::link(
                    "Profile", ["/user/profile/view", "name"=>Yii::app()->user->username],
                    ["class"=>"btn btn-default"]
                )?>
                <?=CHtml::link(
                    "Profile Picture", ["/user/changeAvatar"],
                    ["class"=>"btn btn-default"]
                )?>
            </div>
        </li>
        <li class="list-group-item">
            <span class="badge"><?php
                switch(Yii::app()->user->getModel()->superuser) {
                    case User::R_USER:
                        echo "User";
                        break;
                    case User::R_VIP:
                        echo "VIP";
                        break;
                    case User::R_MOD:
                        echo "Moderator";
                        break;
                    case User::R_ADMIN:
                        echo "Admin";
                        break;
                }
            ?></span>
            You are
        </li>
        <?php if($user->developer): ?>
        <li class="list-group-item">
            <span class="badge alert-warning"><?=($user->developer ? "Yes":"No")?></span>
            Developer
        </li>
        <?php endif; ?>
        <li class="list-group-item">
            <span class="badge alert-info">0</span>
            <p>Private Messages</p>
            <div class="btn-group btn-group-xs">
                <button type="button" class="btn btn-info">Compose</button>
                <button type="button" class="btn btn-info">Inbox</button>
                <button type="button" class="btn btn-info">Outbox</button>
            </div>
        </li>
        <li class="list-group-item">
            <span class="badge alert-success">0</span>
            Characters
        </li>
        <li class="list-group-item">
            <span class="badge alert-success">0</span>
            Art
        </li>
        <li class="list-group-item">
            <span class="badge alert-success">0</span>
            Music
        </li>
        <li class="list-group-item">
            <span class="badge alert-success">0</span>
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
