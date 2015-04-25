// core
var path = require("path");

// Paths
var cdn = path.join(__dirname,"..","cdn");
var app = path.join(cdn,"app");
var theme = path.join(__dirname,"..","themes","dragonsinn");
var cache = path.join(__dirname,"..","cache");

// Config stuff
if(typeof global.config == "undefined") {
    var ini = require("multilevel-ini");
    var me = require("package")(path.join(__dirname,".."));
    var config = ini.getSync(path.join(__dirname, "..", "config/BIRD3.ini"));
    config.base = path.join(__dirname,"..");
    config.version = me.version;
    config.package = me;
} else {
    var config = global.config;
}
config.maxFileSize = 1024*10;

var __debug = global.__debug || false;

// Webpack: Load plugins
var webpack = require("webpack");
var wpConf = require("./webpack.config");
var extractText = require("extract-text-webpack-plugin");
var HashPlugin = require('hash-webpack-plugin');
var bowerwp = require("bower-webpack-plugin");
var stripper = require("strip-loader").loader;

// Webpack: make instances
// Generate the general webpack file - make it a lil' lib.
var commonsPlugin = new webpack.optimize.CommonsChunkPlugin({
    name: "libwebpack",
    filename: "[hash]-libwebpack.js"
});
// Constants that should be distributed namelessly to the front-end
var defines = new webpack.DefinePlugin({
    "__DEV__": JSON.stringify(__debug),
    "__PRERELEASE__": JSON.stringify(true),
    "__VERSION__": JSON.stringify(config.version),
    "__CDN__": JSON.stringify(cdn),
    "__APP__": JSON.stringify(app),
    "__TITLE__": JSON.stringify(config.BIRD3.name),
});
// Bower integration. Mind the excludes!
var bowerPlugin = new bowerwp({
    excludes: /\.(less|sass|scss)/,
});
// This file is read by php_handler.js and given as userData.webpackHash
// Required to properly inject generated sources, enables cache busting.
// I <3 my NodeJS+PHP system!
var assetsp = new HashPlugin({
    path: path.join(config.base, "cache"),
    fileName: "webpack-hash.txt"
});
// The usual jQuery madness.
var provider = new webpack.ProvidePlugin({
    $: "jquery",
    jQuery: "jquery",
    "window.jQuery": "jquery",
    "window.$": "jquery"
});
// Generate bundled CSS. (id, fileName)
var extractor = new extractText("style","[hash]-[name].css");
// Compress and press down our JS.
// FIXME: Learn UglyfyJS
var uglify = new webpack.optimize.UglifyJsPlugin({
    compress: {
        warnings: false,
		sequences: true,
		dead_code: true,
		conditionals: true,
		booleans: true,
		unused: true,
		if_return: true,
		join_vars: true,
		drop_console: true
	},
    sourceMap: true,
    mangle: {
        except: [
            "$oj_oj", "oj",
            "jQuery", "$",
            "require",
            "cloudflare",
            "hljs"
        ]
    }
});
// Querystring for the CSS Loader
var cssq = [
    "keepSpecialComments=0",
    "processImport=true",
    "rebase=true",
    "relativeTo="+config.base,
    "shorthandCompacting=true",
    "target="+app,
    "sourceMap"
].join("?");
// Progress output
var logger = require(config.base+"/node-lib/logger")(config.base);
var progress = new webpack.ProgressPlugin(function(p, msg){
    if(p===0) msg = "Starting compilation...";
    if(p===1) msg = "Done!";
    logger.update("WebPack => [%s%%]: %s", p.toFixed(2)*100, msg);
});

// Return the config
module.exports = {
    context: config.base,
    cache: true,
    debug: __debug,
    watchDelay: 1000*5,
    //devtool: "#inline-source-map",
    entry: {
        main: path.join(__dirname, "../web-lib/main.oj")
    },
    output: {
        // to cdn/app/
        path: app,
        filename: "[hash]-[name].js",
        //chunkFilename: "[name].[id].js",
        sourceMapFilename: "[hash]-[name].[id].map",
        publicPath: "/cdn/app/",
        sourcePrefix: "    "
    },
    resolve: {
        extensions: ["",".js",".json",".oj"],
        modulesDirectories: [
            // NPM, Bower
            'node_modules',
            'bower_components',
            // Yii
            path.join(config.base,"protected/modules"),
            path.join(config.base,"protected/extensions"),
            path.join(config.base,"themes")
        ],
    },
    module: {
        loaders: [
            { // Extract CSS
                test: /\.css$/,
                loader: extractText.extract(
                    "style",
                    "css?"+cssq
                )
            },{ // OJ -> JS
                test: /\.oj$/,
                loader: "oj"
            },{ // WingStyle -> CSS
                test: /\.ws\.php$/,
                loader: extractText.extract(
                    "style",
                    [
                        "css?root="+config.base,
                        path.join(__dirname,"wingstyle-loader.js")
                    ].join("!")
                )
            },{ // Images
                test: /\.(png|jpg|jpeg|gif|svg)/i,
                loader: [
                    // optimize image
                    "img?minimize=true&optimizationLevel=7",
                    // 50kb or smaller
                    "url?limit="+config.maxFileSize,
                ].join("!")
            },{ // Fonts
                test: /\.(eot|woff|woff2|ttf|otf)/,
                loader: "url?limit="+config.maxFileSize
            },{ // Markdown
                test: /\.(md|markdown)$/,
                loader: "markdown"
            },{ // HTML
                test: /\.html$/,
                loader: "html"
            }
        ],
        noParse: [
            /node_modules\/socket\.io-client\/socket\.io\.js$/,
            /\.(min|bundle|pack)\.js$/,
        ]
    },
    plugins: [
        progress,
        commonsPlugin,
        defines,
        provider,
        bowerPlugin,
        extractor,
        assetsp,
        uglify
    ]
};
