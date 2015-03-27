<?php class BIRD3MarkdownEditor extends CWidget {
    public $placement="bottom";
    public $context;
    public $model;
    public $attribute;
    public $autogrow=false;
    public $textDisplay=true;
    public $useWell=true;
    public $editorPlacement="bottom";
    public $groupSize="sm";

    public function run() {
        $this->controller->rqCaret=true;
        $this->render("BIRD3MarkdownEditorView",[
            "form"=>$this->context,
            "model"=>$this->model,
            "attr"=>$this->attribute,
            "wid"=>CHtml::activeId($this->model, $this->attribute)
        ]);
    }
}
