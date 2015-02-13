<?php
/* @var $this SiteController */

$this->pageTitle="Home";
#$this->allPage=true;
#$this->tabbar="WHADAFU1";
#$this->leftSide="some app links";
#$this->rightSide="o3o";
?>

<h3>This is a demo!</h3>
<p>
    This demo does nothing but looking pretty. Keep this in mind.
</p>
<p>
    To get more information, see the <code>TODO.md</code> file at the
    <a href="http://git.ingwie.me/ingwie/bird3" target="_blank">source repository</a>.
</p>

<h4>Try out some small things.</h4>
<script type="text/javascript">
function p_test() {
    app.prompt({
        text: "This is cool",
        placeholder: "And this is shady."
    }, function(data){
        app.alert("Got: "+data);
    });
};
</script>
<p>
    Click this, to show the new dialogs.
    <button onclick="p_test();" id="dlgt" class="btn btn-info">Trigger dialog</button>
</p>
<p>
    Click for an alert.
    <button onclick="alert('Yo.')" class="btn btn-success">Alert</button>
</p>
<p>
    A row of alerts.
    <button onclick="alert('Default', app.getTitle(), 'type-default')" class="btn btn-default">
        Default
    </button>
    <button onclick="alert('Info', app.getTitle(), 'type-info')" class="btn btn-info">
        Info
    </button>
    <button onclick="alert('Primary', app.getTitle(), 'type-primary')" class="btn btn-primary">
        Primary
    </button>
    <button onclick="alert('Success', app.getTitle(), 'type-success')" class="btn btn-success">
        Success
    </button>
    <button onclick="alert('Warning', app.getTitle(), 'type-warning')" class="btn btn-warning">
        Warn
    </button>
    <button onclick="alert('Eep!', 'Error', 'type-danger')" class="btn btn-danger">
        Danger
    </button>
</p>
<p>
    Layout: <?=$this->layout?>
</p>
