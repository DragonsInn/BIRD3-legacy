<h2>See all our fellow users!</h2>
<p class="lead">You can see the latest newcommers or find your friends or artists you are looking for. Use the search to do so!</p>

<div class="well">
    <form method="get" class="form-horizontal" name="user_search">
        <div class="form-group">
            <label class="col-sm-4 control-label" for="q">Search for a username or an E-Mail address</label>
            <div class="col-sm-6">
                <?php $qval = (isset($_GET["q"]) ? 'value="'.$_GET["q"].'"' : ""); ?>
                <input type="text" class="form-control" name="q"<?=$qval?>>
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-default" id="search_user">
                    Search
                </bottom>
            </div>
        </div>
    </form>
</div>
<hr>
<div class="row">
    <?php foreach($users as $user): ?>
        <div class="col-sm-12 col-md-4">
            <div class="well">
                <div class="media">
                    <div class="media-left">
                        <?=CHtml::image(User::avatarUrl($user->id), $user->username."'s avatar", [
                            "class"=>"media-object img-thumbnail",
                        ])?>
                    </div>
                    <div class="media-body">
                        <p class="media-heading" style="font-size:18px;">
                            <?=$user->username?>
                        </p>
                        <div><b>Joined at:</b> <?=date("dS F Y", $user->create_at)?></div>
                        <div><a href="#">Send a PM</a></div>
                        <div><?=CHtml::link("Profile", ["/user/profile/view","name"=>$user->username])?></div>
                        <div><a href="#">Characters</a></div>
                        <div><a href="#">Blog</a></div>
                        <div><a href="#">Galleries</a></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<hr>
<nav>
    <ul class="pagination">
        <li>
            <a href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php for($i=1; $i<=$pages; $i++): ?>
        <li><?=CHtml::link($i, [
            "/user/list","page"=>$i
        ] + (isset($_GET["q"])
            ? ["q"=>$_GET["q"]]
            : []
        ))?></li>
        <?php endfor; ?>
        <li>
            <a href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
  </ul>
</nav>
