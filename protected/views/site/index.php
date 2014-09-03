<?php
/* @var $this SiteController */

$this->pageTitle="Home";

$this->tabbar=<<<EOF
<nav class="navbar navbar-inverse" role="navigation">
	<p class="navbar-text">
 		waddup
	</p>
</nav>
EOF;
#$this->leftSide="some app links";
$this->rightSide=<<<EOF
	<p>
		This bar should be used to provide useful help.
	</p>
	<p>
		On smaller screen width, it shall be gone.
	</p>
EOF;
?>

<h1>Latest Characters...</h1>
<div>
	<div style="width:50%; position:relative; float: left;">
		this is div 1
	</div>
	<div style="width:50%; position:relative; float: left;">
		This is div 2
	</div>
</div>
