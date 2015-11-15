<div>
    <ul class="list-group">
        <li class="list-group-item list-group-item-soft">
            <span class="pull-right"><?=HTML::link(
                "/user/logout", "Logout",
                ["class"=>"btn btn-danger btn-xs"]
            )?></span>
            <p>Yourself</p>
            <div class="btn-group btn-group-xs">
                <?=HTML::link(
                    "/user/settings", "Settings",
                    ["class"=>"btn btn-default"]
                )?>
                <?=HTML::link(
                    # FIXME: Link to own profile.
                    "/user/profile", "Profile",
                    ["class"=>"btn btn-default"]
                )?>
                <?=HTML::link(
                    "/user/change-avatar", "Avatar",
                    ["class"=>"btn btn-default"]
                )?>
            </div>
        </li>
        <li class="list-group-item list-group-item-soft">
            <span class="badge">
                <?php #$this->roleToString()?>
            </span>
            You are
        </li>
        <?php if($this->developer): ?>
        <li class="list-group-item list-group-item-soft">
            <span class="badge alert-warning"><?=($this->developer ? "Yes":"No")?></span>
            Developer
        </li>
        <?php endif; ?>
        <li class="list-group-item list-group-item-soft">
            <span class="badge alert-info">0</span>
            <p>Private Messages</p>
            <div class="btn-group btn-group-xs">
                <button type="button" class="btn btn-info">Compose</button>
                <?=HTML::link(
                    "/user/pm/box", "Inbox",
                    ["class"=>"btn btn-info"]
                )?>
                <button type="button" class="btn btn-info">Outbox</button>
            </div>
        </li>
        <li class="list-group-item list-group-item-soft">
            <span class="badge alert-success">0</span>
            Characters
        </li>
        <li class="list-group-item list-group-item-soft">
            <span class="badge alert-success">0</span>
            Art
        </li>
        <li class="list-group-item list-group-item-soft">
            <span class="badge alert-success">0</span>
            Music
        </li>
        <li class="list-group-item list-group-item-soft">
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
