// core
var path = require("path");
var merge = require("merge");
var a_merge = require("array-merger").merge;
var glob = require("glob");
var fs = require("fs");

// Paths
var cdn = path.join(__dirname,"..","cdn");
var app = path.join(cdn,"app");
var theme = path.join(__dirname,"..","app/App/Frontend/");
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
var base = config.base;
config.maxFileSize = 1024*10;

var __debug = global.__debug || process.env["BIRD3_DEBUG"]==true || false;
var _jquery = path.join( config.base, "app/App/Entry/Browser/Support/jQueryize.js" );
var _ojr = require.resolve("ojc/src/runtime");

// Webpack: Load plugins
var webpack = require("webpack");
var extractText = require("extract-text-webpack-plugin");
var HashPlugin = require('hash-webpack-plugin');
var cleanPlugin = require('clean-webpack-plugin');
var purify = require("./purify-plugin");

// postcss
var mergeRules = require('postcss-merge-rules')

// OJ
var juicy = require("oj-loader").juicy;

// Webpack: make instances
// Generate the general webpack file - make it a lil' lib.
var commonsPlugin = new webpack.optimize.CommonsChunkPlugin({
    name: "libwebpack",
    filename: "[hash]-libwebpack.js",
    //children: true,
    //async: true,
    minChunks: Infinity
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
var uglify = new webpack.optimize.UglifyJsPlugin(require("./uglifyjs.config.js"));
// Querystring for the CSS Loader
var cssq = [
    "keepSpecialComments=0",
    "processImport=true",
    "rebase=true",
    "relativeTo="+config.base,
    "shorthandCompacting=true",
    "target="+app,
    "sourceMap"
].join("&");
// Configure SASS
var sassq = [
    "includePaths[]="+path.join(
        config.base, "bower_components",
        "bootstrap-sass/assets/stylesheets"
    ),
    "includePaths[]="+path.join(config.base, "bower_components"),
    "includePaths[]="+path.join(config.base, "node_modules"),
    "includePaths[]="+path.join(config.base, "themes"),
    "sourceMap"
].join("&");
// Progress output
var logger = require(config.base+"/app/Backend/logger")(config.base);
var progress = new webpack.ProgressPlugin(function(p, msg){
    if(p===0) msg = "Starting compilation...";
    if(p===1) msg = "Done!";
    logger.update("WebPack => [%s%%]: %s", p.toFixed(2)*100, msg);
});
// Try to press down further
var dedupe = new webpack.optimize.DedupePlugin();

// Banner
var bannerPlugin = new webpack.BannerPlugin((function(){
    return require("ejs").render(
        fs.readFileSync(path.join(__dirname, "banner.ejs"), "utf8"),
        {
            version: config.version
        },{
            filename: path.join(__dirname, "banner.ejs"),
            rmWhitespace: false
        }
    );
})(), {raw: true, entryOnly: false});

// Clear plugin
var clear = new cleanPlugin(["cdn/app"], config.base);

// PurifyCSS
var purifyPlugin = new purify({
    basePath: config.base,
    paths: a_merge([
        // Yii views
        "app/App/Views/*/*.php",
        "app/App/Modules/*/Views/*/*.php",
        "app/Extensions/*/Views/*.php",
        "app/Frontend/Design/Layouts/*.php",
        // JavaScript + OJ
        "app/App/Entry/Browser/*.oj",
        "app/App/Entry/Browser/*.js",
        "app/App/Entry/Browser/Support/*.oj",
        "app/App/Entry/Browser/Support/*.js",
        "app/Frontend/*.oj",
        "app/Frontend/*.js",
        "app/Frontend/Frameworks/*.js",
        "app/Frontend/Frameworks/*.oj",
        // EJS
        //"web_modules/frameworks/*/*.ejs",
        //"app/extensions/*/js/*/*.ejs",
        // Specific
        "app/App/Modules/Chat/js/*.js",
        //"app/modules/chat/lib/template/*.html",
        //"app/modules/chat/lib/template/*.php",
        // Ladda
        "node_modules/ladda/js/*.js",
        "node_modules/ladda/node_modules/spin.js/spin.js",
        // bootstrap.native
        // "bower_components/bootstrap.native/lib/*.js"
    ], (function(){
        var bnm = [];
        [
            "modal","tooltip","popover",
            "dropdown","button"
        ].forEach(function(entry){
            bnm.push(entry);
        });
        return bnm;
    })())
});

// Return the config
module.exports = {
    context: config.base,
    cache: true,
    debug: __debug,
    watchDelay: 1000*5,
    //devtool: "#source-map",
    entry: {
        libwebpack: [
            "domready", _jquery, _ojr,
            "BIRD3/App/Entry/Browser/Support/Common",
            "BIRD3/App/Entry/Browser/Support/BootstrapNative",
            "BIRD3/Frontend/Design/panels.js"
        ],
        main: "BIRD3/App/Entry/Browser/Main"
    },
    output: {
        // to cdn/app/
        path: app,
        filename: "[hash]-[name].js",
        chunkFilename: "[hash]-[name].[id].js",
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
            path.join(config.base,"app/App/Modules"),
            path.join(config.base,"app/Extensions"),
        ],
        modulesDirectories: [
            // Bower, NPM, Composer, Web
            'bower_components',
            'node_modules',
            'php_modules',
            'web_modules'
        ],
        alias: {
            // This is sooooo cool!
            BIRD3: path.join(config.base, "app"),
            // Ensure compatibility to original bootstrap
            bootstrap: "BIRD3/App/Entry/Browser/Support/BootstrapNative.js",
            "a11y.bs": path.join(
                config.base,
                "bower_components",
                "bootstrapaccessibilityplugin/src"
            ),
            jquery: _jquery,
            ws: "ws/lib/browser",
            "LDT": path.join(
                config.base,
                "web_modules/LDT.webpack.js"
            ),
            "behave.js": "behave.js/behave.js"
        }
    },
    module: {
        loaders: [
            { // Extract CSS
                test: /\.css$/,
                loader: extractText.extract(
                    "style",
                    "css?"+cssq+"!postcss?"+cssq
                )
            },{ // Extract Sassy CSS
                test: /\.scss$/,
                loader: extractText.extract(
                    "style",
                    [
                        "css?"+cssq,
                        "postcss?"+cssq,
                        "sassport?"+sassq
                    ].join("!")
                )
            },{ // WingStyle -> CSS
                test: /\.ws\.php$/,
                loader: extractText.extract(
                    "style",
                    [
                        "css?"+cssq,
                        "postcss?"+cssq,
                        path.join(__dirname,"wingstyle-loader.js")
                    ].join("!")
                )
            },{ // Images
                test: /\.(png|jpg|jpeg|gif|svg)/i,
                loader: [
                    // optimize image
                    "img?minimize=true&optimizationLevel=7",
                    // 50kb or smaller
                    "file",
                ].join("!")
            },{ // Fonts
                test: /\.(eot|woff|woff2|ttf|otf|mp3|wav|ogg|swf)/,
                loader: "file"
            },{ // Markdown
                test: /\.md$/,
                loader: "markdown"
            },{ // HTML
                test: /\.html$/,
                loader: "html"
            },{ // Embedded JS templates
                test: /\.ejs$/,
                loader: "ejs-compiled?delimiter=?&+rmWhitespaces"
            },{ // Load JSON. How stupid is this...?
                test: /\.json$/,
                loader: "json",
            },{ // OJ -> JS
                test: /\.oj$/,
                loader: "oj?-warn-unknown-ivars&-warn-unknown-selectors"
            },{ // Webfont generator
                test: /\.font\.(js|json)$/,
                loader: "fontgen"
            }
        ],
        noParse: [
            /\.(min|bundle|pack)\.js$/,
        ]
    },
    postcss: [mergeRules()],
    oj: {
        runtime: _ojr,
        pre: [ juicy.preprocessor() ],
        options: {
            preprocessor: {
                include_path: [
                    theme,
                    path.join(base, "App/Frontend/Frameworks"),
                ],
                defines: {},
            }
        }
    },
    plugins: [
        // Output
        //progress,
        assetsp,
        // Cosmetics
        clear, bannerPlugin,
        // Chunking
        commonsPlugin, dedupe,
        // Module enhancements
        defines, provider, bowerProvider,
        // CSS
        extractor, purifyPlugin,
        // JavaScript
        //uglify
    ]
};
