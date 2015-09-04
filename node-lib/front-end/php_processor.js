var cheerio = require("cheerio");
var hljs = require("highlight.js");
var htmlminify = require("html-minifier").minify;
var path = require("path");

hljs.configure({
    tabReplace: Array(5).join(" ")
});

module.exports = function(php) {
    // # Request procession

    // # Pre-Processor

    // # Post-Processor

    // HighlightJS
    php.use("postprocess",function(ctx,next){
        var $ = ctx.$ = cheerio.load(ctx.php.body, {decodeEntities: false});
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

    // Minify the HTML
    php.use("postprocess", function(ctx, next){
        if(typeof ctx.req.query.dev == "undefined") {
            ctx.php.body = htmlminify(ctx.php.body, {
                collapseWhitespace: true,
                removeComments: true,
                removeCommentsFromCDATA: true,
                conservativeCollapse: false,
                removeAttributeQuotes: true,
                removeRedundantAttributes: true,
                removeScriptTypeAttributes: true,
                minifyJS: require(path.join(config.base, "util/uglifyjs.config.js")),
                minifyCSS: true
            });
        }
        next();
    });
}
