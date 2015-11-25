    <ul class="nav nav-tabs" id="<?=$this->id?>">
        <?php foreach($this->links as $info) { ?>
        <?php $liID = (isset($info["id"]) ? 'id="'.$info['id'].'"' : null); ?>
        <?php $aClass = (isset($info["class"]) ? 'class="'.$info["class"].'"' : null); ?>
        <li <?=$aClass?>><a <?=$liID?> href="<?=CHtml::normalizeUrl($info["url"])?>">
            <?php if(!is_null($info['mini'])){ ?><span class="show-onMini"><?=$info["mini"]?></span><?php } ?>
            <?php if(!is_null($info['medium'])){ ?><span class="show-onMedium"><?=$info["medium"]?></span><?php } ?>
            <?php if(!is_null($info['large'])){ ?><span class="show-onLarge"><?=$info["large"]?></span><?php } ?>
            <?php if(!is_null($info['big'])){ ?><span class="show-onBig"><?=$info["big"]?></span><?php } ?>
        </a></li>
        <?php } ?>
    </ul>
