var cheerio = require("cheerio");
var hljs = require("highlight.js");
var htmlminify = require("html-minify").minify;

hljs.configure({
    tabReplace: Array(5).join(" ")
});

module.exports = function(php) {
    // Request procession

    // Pre-Processor

    // Post-Processor
    php.use("postprocess",function(ctx,next){
        // HighlightJS
        var $ = cheerio.load(ctx.php.body, {decodeEntities: false});
        if($("body").find("pre code").length > 0) {
            $("body").find("pre code").each(function(i,v){
                if($(v).attr("class").match(/language-.+/ig) != null) {
                    // The current block has a language- class.
                    var cn = $(v).attr("class").replace("language-","");
                    $(v).addClass("hascode");
                    $(v).parent().addClass("hascode");
                    var out = hljs.highlight(cn,$(v).html());
                    $(v).html(out.value);
                }
            });
        }
        ctx.php.body = $.html();
        next();
    });
    php.use("postprocess", function(ctx, next){
        if(typeof ctx.req.query.dev == "undefined") {
            // Minify the HTML
            ctx.php.body = htmlminify(ctx.php.body, {
                collapseWhitespace: true,
                removeComments: true
            });
        }
        next();
    });
}
