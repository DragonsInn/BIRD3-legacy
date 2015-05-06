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
//var _jquery = "jquip/dist/jquip.all"; // 154 kb
//var _jquery = "zepto/src/zepto"; // 135 kb
//var _jquery = "cash/dist/cash.js"; // 129 kb
var _jquery = "jquery"; // 207 kb

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
var bowerProvider = new webpack.ResolverPlugin([
    new webpack.ResolverPlugin.DirectoryDescriptionFilePlugin("bower.json", ["main"]),
    new webpack.ResolverPlugin.DirectoryDescriptionFilePlugin(".bower.json", ["main"])
], ["normal", "loader"]);
// This file is read by php_handler.js and given as userData.webpackHash
// Required to properly inject generated sources, enables cache busting.
// I <3 my NodeJS+PHP system!
var assetsp = new HashPlugin({
    path: path.join(config.base, "cache"),
    fileName: "webpack-hash.txt"
});
// The usual jQuery madness.
var provider = new webpack.ProvidePlugin({
    $: _jquery,
    jQuery: _jquery,
    "window.jQuery": _jquery,
    "window.$": _jquery
});
// Generate bundled CSS. (id, fileName)
var extractor = new extractText("style","[hash]-[name].css");
// Compress and press down our JS.
// FIXME: Learn UglyfyJS
var uglify = new webpack.optimize.UglifyJsPlugin({
    compress: {
        warnings: false,
        properties: true,
		sequences: true,
		dead_code: true,
		conditionals: true,
        comparisons: true,
        evaluate: true,
		booleans: true,
		unused: true,
        loops: true,
        hoist_funs: true,
        cascade: true,
		if_return: true,
		join_vars: true,
		//drop_console: true,
        drop_debugger: true,
        negate_iife: true,
        unsafe: true
	},
    sourceMap: true,
    mangle: {
        except: [
            "$oj_oj", "oj",
            "jQuery", "$",
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
    //"sourceMap"
].join("&");
// Configure SASS
var sassq = [
    "includePaths[]="+path.join(
        config.base, "bower_components",
        "bootstrap-sass/assets/stylesheets"
    ),
    "includePaths[]="+path.join(config.base, "bower_components"),
    "includePaths[]="+path.join(config.base, "node_modules"),
    "includePaths[]="+path.join(config.base, "themes")
].join("&");
// Progress output
var logger = require(config.base+"/node-lib/logger")(config.base);
var progress = new webpack.ProgressPlugin(function(p, msg){
    if(p===0) msg = "Starting compilation...";
    if(p===1) msg = "Done!";
    logger.update("WebPack => [%s%%]: %s", p.toFixed(2)*100, msg);
});
// Try to press down further
var dedupe = new webpack.optimize.DedupePlugin();

// Return the config
module.exports = {
    context: config.base,
    cache: true,
    debug: __debug,
    watchDelay: 1000*5,
    devtool: "#source-map",
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
        extensions: [
            "", // Support supplied extensions.
            ".js", ".oj", // JavaScript
            ".json", // Structured data
            ".css", ".scss" // Styles
        ],
        root: [
            config.base,
            // Yii
            path.join(config.base,"protected/modules"),
            path.join(config.base,"protected/extensions"),
            path.join(config.base,"themes")
        ],
        modulesDirectories: [
            // NPM, Bower
            'bower_components',
            'node_modules',
        ],
        alias: {
            debug: path.join(
                config.base,
                "node_modules",
                "debug"
            ),
            // Ensure compatibility to original bootstrap
            "bootstrap.js": path.join(
                config.base,
                "bower_components",
                "bootstrap-sass/assets/javascripts/bootstrap"
            ),
            bootstrap: path.join(
                config.base,
                "web-lib/bootstrapper.js"
            ),
            "a11y.bs": path.join(
                config.base,
                "bower_components",
                "bootstrapaccessibilityplugin/src"
            ),
            jquery: _jquery,
            /*"jquery.js": path.join(
                config.base,
                "bower_components",
                "jquery/src"
            ),*/
            ws: "ws/lib/browser"
        }
    },
    module: {
        loaders: [
            { // Extract CSS
                test: /\.css$/,
                loader: extractText.extract(
                    "style",
                    "css?"+cssq
                )
            },{ // Extract Sassy CSS
                test: /\.scss$/,
                loader: extractText.extract(
                    "style",
                    "css?"+cssq+"!sass?"+sassq
                )
            },{ // WingStyle -> CSS
                test: /\.ws\.php$/,
                loader: extractText.extract(
                    "style",
                    [
                        "css?"+cssq,
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
            },{ // OJ -> JS
                test: /\.oj$/,
                loader: "oj"
            },
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
        bowerProvider,
        extractor,
        assetsp,
        dedupe,
        uglify
    ]
};
