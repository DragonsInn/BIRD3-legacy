// Processors:
import cheerio from "cheerio";
import hljs from "highlight.js";
import {minify as htmlminify} from "html-minifier";
import {transform as Babel} from "babel-core";

// Regular
import path from "path";
import temp from "temp";
import mime from "mime";
import fs from "fs";
import BIRD3 from "BIRD3/Support/GlobalConfig";
import RedisClient from "redis";


var redis = RedisClient.createClient();
var log = BIRD3.log.makeGroup("PHP Handler");

hljs.configure({
    tabReplace: Array(5).join(" ")
});

module.exports = function(php) {
    // # Request procession

    // # Pre-Processor

    // Fetch WebPack key and insert
    php.use("preprocess", function GetWebPackHash(wareCtx, next){
        redis.get(BIRD3.WebPackKey, function(err, res){
            if(err) {
                log.notice("Unable to get WebPack hash.");
                return next(err);
            }
            //log.info("Hash: "+res);
            wareCtx.ctx.optional.wpHash = res;
            next();
        });
    });

    // Convert input files.
    // FIXME: Do we still need this? o.o
    /*
        NodeJS files:
        req.files = {
            name: {
                fieldName: '',
                originalFilename: pathToTempFile,
                headers: { name: value },
                ws: { NodeJS WriteStream },
                size: N,
                name: '',
                type: MimeType
            }
        }

        FileZone uses these headers:
        {

        }

        PHP $_FILES:
        $_FILES = [
            FieldName => [
                name => "",
                size => $n,
                type => $MimeType,
                tmp_name => $pathToTempFile,
                error => 0
            ]
        ];
    */
    /*
    php.use("preprocess", function ConvertFilesData(wareCtx, next){
        var ctx = wareCtx.ctx;
        // This implementation is incomplete.
        // FIXME: Multiple files, HTML array

        var FilesFromFiles = function() {
            var files = ctx.reqest.files;
            var $_FILES = {};
            for(var id in files) {
                var file = files[id];
                $_FILES[id] = {
                    name: new String(file.name),
                    size: new Number(file.size),
                    type: new String(file.type),
                    tmp_name: new String(file.path),
                    error: 0
                }
            }
            ctx.arg.request._FILES = $_FILES;
            next();
        };
        var FilesFromDrop = function() {
            var hdr = ctx.request.headers;
            var $_FILES = {};
            $_FILES[hdr["x-file-input"]] = {
                name: hdr["x-file-name"],
                size: hdr["x-file-size"],
                type: hdr["x-file-type"],
                error: 0,
            };
            var path = $_FILES[hdr["x-file-input"]].tmp_name = temp.path({
                suffix: "."+mime.extension(hdr["x-file-type"])
            });
            fs.writeFile(path, ctx.request.body, function(err) {
                if(err) return next(err);
                else {
                    ctx.arg.request._FILES = $_FILES;
                    next();
                }
            });
        };
        if(ctx.request.method=="POST") {
            var hdr = ctx.request.headers;
            if(
                typeof hdr["x-requested-with"] != "undefined"
                && /^filedrop/i.test(hdr["x-requested-with"])
            ) {
                FilesFromDrop();
            } else {
                FilesFromFiles();
            }
        } else next();
    });
    */

    // # Post-Processor

    // HighlightJS / Babel
    php.use("postprocess",function(wareCtx,next){
        var ctx = wareCtx.ctx;
        var $ = ctx.$ = cheerio.load(ctx.response.body, {decodeEntities: false});
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
        // Enable ES6 on the client. Should be useful with JSX and o.o
        $("body").find("script.es6").each(function(i,v){
            console.log(Babel)
            var source = $(v).text();
            var newSource = Babel(source, {
                presets: [ 'es2015', 'stage-1' ],
                plugins: [
                    ["syntax-jsx"],
                    ["transform-react-jsx",{
                        pragma: "window.oo"
                    }]
                ]
            }).code;
            $(v).text(newSource);
        });
        ctx.response.body = $.html();
        next();
    });

    // Minify the HTML
    php.use("postprocess", function(wareCtx, next){
        var ctx = wareCtx.ctx;
        if(typeof ctx.request.query.dev == "undefined") {
            ctx.response.body = htmlminify(ctx.response.body, {
                collapseWhitespace: true,
                removeComments: true,
                removeCommentsFromCDATA: true,
                conservativeCollapse: false,
                removeAttributeQuotes: true,
                removeRedundantAttributes: true,
                removeScriptTypeAttributes: true,
                minifyJS: require("BIRD3/System/Config/uglifyjs"),
                minifyCSS: true
            });
        }
        next();
    });
}
