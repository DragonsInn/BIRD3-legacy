<?php class BIRD3MarkdownEditor extends CWidget {
    public $placement="bottom";
    public $taClass = "";
    public $context;
    public $model;
    public $attribute;
    public $autogrow=false;
    public $textDisplay=true;
    public $useWell=true;
    public $editorPlacement="bottom";
    public $groupSize="sm";
    public $placeholder="";
    public $height="200px";

    public function run() {
        # Render a div that will contain the editor.
        echo CHtml::tag("div", [
            "id"=>CHtml::activeId($this->model, $this->attribute),
            #"id"=>$wid,
            "class"=>($this->useWell ? "well well-sm" : ""),
            "data-b3me"=>true,
            "data-name"=>CHtml::activeId($this->model, $this->attribute),
            "data-ta-class"=>$this->taClass,
            "data-text-display"=>$this->textDisplay,
            "data-group-size"=>$this->groupSize,
            "data-placeholder"=>htmlspecialchars($this->placeholder),
            "data-placement"=>htmlspecialchars($this->placement),
            "data-editor-placement"=>htmlspecialchars($this->editorPlacement)
        ], "");
    }
}
