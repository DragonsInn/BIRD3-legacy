<div>
    <?php $user = Yii::app()->user; ?>
    <ul class="list-group">
        <li class="list-group-item">
            <span class="badge"><?=$user->username?></span>
            <p>You</p>
            <div class="btn-group btn-group-xs">
                <?=CHtml::link(
                    "Profile", ["user/profile/view", "id"=>Yii::app()->user->id],
                    ["class"=>"btn btn-default"]
                )?>
                <button type="button" class="btn btn-default">Settings</button>
                <?=CHtml::link(
                    "Logout", ["/user/logout"],
                    ["class"=>"btn btn-danger"]
                )?>
            </div>
        </li>
        <li class="list-group-item">
            <span class="badge"><?php
                switch(Yii::app()->user->loadUser()->superuser) {
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
        <li class="list-group-item">
            <span class="badge alert-warning"><?=($user->developer ? "Yes":"No")?></span>
            Developer
        </li>
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
