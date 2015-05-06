define([
	"jquery.js/core",
    "jquery.js/sizzle/dist/sizzle",
    "jquery.js/event",
    "jquery.js/event/alias",
	"jquery.js/traversing",
	"jquery.js/callbacks",
	"jquery.js/deferred",
	"jquery.js/core/ready",
	"jquery.js/data",
	"jquery.js/queue",
	"jquery.js/queue/delay",
	"jquery.js/attributes",
	"jquery.js/manipulation",
	"jquery.js/manipulation/_evalUrl",
	"jquery.js/wrap",
	"jquery.js/css",
	"jquery.js/css/hiddenVisibleSelectors",
	"jquery.js/serialize",
	"jquery.js/ajax",
	"jquery.js/ajax/xhr",
	"jquery.js/ajax/script",
	"jquery.js/ajax/jsonp",
	"jquery.js/ajax/load",
	"jquery.js/event/ajax",
	"jquery.js/effects",
	"jquery.js/effects/animatedSelector",
	"jquery.js/offset",
	"jquery.js/dimensions",
	"jquery.js/deprecated"
], function( jQuery, Sizzle ) {

// Hotfix Sizzle
jQuery.find = Sizzle;
jQuery.expr = Sizzle.selectors;
jQuery.expr[":"] = jQuery.expr.pseudos;
jQuery.unique = Sizzle.uniqueSort;
jQuery.text = Sizzle.getText;
jQuery.isXMLDoc = Sizzle.isXML;
jQuery.contains = Sizzle.contains;

return jQuery;

});
