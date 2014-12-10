<?php Yii::app()->clientScript->registerScript(
    "switchInit", '$("select").ddselect({
        buttonClass: "btn-default",
        sizeClass: "btn-xs"
    });
    $("input[type=\"checkbox\"]").checkboxpicker({
        style: "somestupidclass",
        defaultClass: "btn-default btn-xs",
        offClass: "btn-danger btn-xs",
        onClass: "btn-success btn-xs"
    });',
    CClientScript::POS_READY
); ?>
<?php $form=$this->beginWidget("CActiveForm", array(
    'id'=>'character-form',
    'focus'=>array($model,'name')
)); ?>

<?php $this->widget("BIRD3Tabbar",array(
    "brand"=>"Sections",
    "updateHash"=>false,
    "entries"=>array(
        "Basic"=>array("#Basic"),
        "Birth and Death"=>array("#BirthAndDeath"),
        "Alignment"=>array("#Alignment"),
        "Story"=>array("#Story"),
        "Appearance"=>array("#Appearance"),
        "Adult"=>array("#Adult", "class"=>"alert alert-danger")
    )
)); ?>

<div id="tab-content">
    <div id="Basic">
        <div class="row">
            <div class="form-group">
                <h3>
                    <?=$form->labelEx($model, "name", array(
                        "label"=>"Character's name",
                        "class"=>"col-md-4 control-label"
                    ))?>
                    <div class="col-md-8">
                        <?=$form->textField($model, "name", array("class"=>"form-control input-lg white-box"))?>
                    </div>
                </h3>
            </div>
        </div>
        <br style="height:20px;"/>
        <div class="row">
            <div class="col-md-6">
                <ul class="list-group">
                    <li class="list-group-item">
                        <?=$form->labelEx($model, "species", array("class"=>"col-md-6"))?>
                        <?=$form->textField($model, "species", array(
                            "class"=>"form-control",
                            "placeholder"=>"Phoenix, Dragon, Fox, ..."
                        ))?>
                    </li>
                    <li class="list-group-item">
                        <?=$form->labelEx($model, "importance", array("class"=>"col-md-6"))?>
                        <?=$form->dropDownList($model, "importance", $model->listImportance(), array(
                            "data-style"=>"col-md-6 form-control"
                        ))?>
                    </li>
                    <li class="list-group-item">
                        <?=$form->labelEx($model, "sex", array("class"=>"col-md-6"))?>
                        <?=$form->dropDownList($model, "sex", $model->listSex(), array(
                            "data-style"=>"col-md-6 form-control"
                        ))?>
                    </li>
                    <li class="list-group-item">
                        <?=$form->labelEx($model, "orientation", array("class"=>"col-md-6"))?>
                        <?=$form->dropDownList($model, "orientation", $model->listOrientation(), array(
                            "data-style"=>"col-md-6 form-control"
                        ))?>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul class="list-group">
                    <li class="list-group-item">
                        <?=$form->labelEx($model, "nickName", array("class"=>"col-md-6"))?>
                        <?=$form->textField($model, "nickName", array("class"=>"form-control"))?>
                    </li>
                    <li class="list-group-item">
                        <?=$form->labelEx($model, "adult", array(
                            "label"=>"Content type",
                            "class"=>"col-md-6"
                        ))?>
                        <?=$form->checkBox($model, "adult", array(
                            "data-off-label"=>"Clean",
                            "data-on-label"=>"Adult",
                            "data-off-class"=>"btn-primary btn-xs",
                            "data-on-class"=>"btn-danger btn-xs",
                        ))?>
                    </li>
                    <li class="list-group-item">
                        <?=$form->labelEx($model, "style", array("class"=>"col-md-6"))?>
                        <?=$form->dropDownList($model, "style", $model->listStyle(), array(
                            "data-style"=>"col-md-6"
                        ))?>
                    </li>
                    <li class="list-group-item">
                        <?=$form->labelEx($model, "visibility", array("class"=>"col-md-6"))?>
                        <?=$form->dropDownList($model, "visibility", $model->listVisibility(), array(
                            "data-style"=>"col-md-6 form-control"
                        ))?>
                    </li>
                </ul>
            </div>
        </div>
        <hr>
        <div id="row">
            <h4><?=$form->labelEx($model, "personality")?></h4>
            <div class="panel panel-dark">
                <div class="panel-body">
                    <?=$form->textArea($model, "personality", array(
                        "class"=>"form-control col-md-12 input-lg",
                        "rows"=>5,
                        "placeholder"=>"This is a summary of your character. Describe him/her shortly."
                    ))?>
                </div>
            </div>
        </div>
    </div>

    <div id="BirthAndDeath">
        <h4>Birth and Death <small>Your character's CV, in a away.</small></h4>
        <div class="well">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?=$form->labelEx($model, "birthday", array("class"=>"col-md-4 control-label"))?>
                        <div class="col-md-8">
                            <?=$form->textField($model, "birthday", array(
                                "class"=>"form-control",
                                "placeholder"=>"Date (I.e.: DD.MM.YYYY)"
                            ))?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?=$form->labelEx($model, "birthPlace", array("class"=>"col-md-4 control-label"))?>
                        <div class="col-md-8">
                            <?=$form->textField($model, "birthPlace", array(
                                "class"=>"form-control",
                                "placeholder"=>"I.e.: Dallas, Texas, USA"
                            ))?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="well">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?=$form->labelEx($model, "spirit_death_date", array("class"=>"col-md-4 control-label"))?>
                        <div class="col-md-8">
                            <?=$form->textField($model, "spirit_death_date", array(
                                "class"=>"form-control",
                                "placeholder"=>"Date (I.e.: DD.MM.YYYY)"
                            ))?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?=$form->labelEx($model, "spirit_death_place", array("class"=>"col-md-4 control-label"))?>
                        <div class="col-md-8">
                            <?=$form->textField($model, "spirit_death_place", array(
                                "class"=>"form-control",
                                "placeholder"=>"I.e.: At home."
                            ))?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?=$form->labelEx($model, "spirit_death_cause", array("class"=>"col-md-4 control-label"))?>
                        <div class="col-md-8">
                            <?=$form->textField($model, "spirit_death_cause", array(
                                "class"=>"form-control",
                                "placeholder"=>"I.e.: Due to his/her age."
                            ))?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="Alignment">
    </div>

    <div id="Story">
    </div>

    <div id="Appearance">
    </div>

    <div id="Adult">
    </div>
</div>


<?php $this->endWidget(); ?>
