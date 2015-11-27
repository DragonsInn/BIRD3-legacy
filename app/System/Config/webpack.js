// Find root
var root = require("find-root")();

// core
var path = require("path");
var merge = require("merge");
var a_merge = require("array-merger").merge;
var glob = require("glob");
var fs = require("fs");

// Global
require(path.join(root,"app/bootstrap/nodejs/autoload.js"));


// Paths
var cdn = path.join(root,"cdn");
var app = path.join(cdn,"app");
var theme = path.join(app,"App/Frontend/");
var cache = path.join(root,"cache");

// Config
var BIRD3 = require("BIRD3/Support/GlobalConfig");
var config = BIRD3.config;
config.maxFileSize = 1024*10;
config.base = BIRD3.root;

var __debug = global.__debug || process.env["BIRD3_DEBUG"]==true || false;
var _jquery = path.join( config.base, "app/App/Entry/Browser/Support/jQueryize.js" );
var _ojr = require.resolve("ojc/src/runtime");

// Webpack: Load plugins
var webpack = require("webpack");
var extractText = require("extract-text-webpack-plugin");
var HashPlugin = require('hash-webpack-plugin');
var cleanPlugin = require('clean-webpack-plugin');
var purify = require("bird3-purifycss-webpack-plugin");

// postcss
var mergeRules = require('postcss-merge-rules');

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
var extractor = new extractText("style","[hash]-[name].css",{allChunks:true});
// Compress and press down our JS.
var uglify = new webpack.optimize.UglifyJsPlugin(require("BIRD3/System/Config/uglifyjs"));
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
var progress = new webpack.ProgressPlugin(function(p, msg){
    if(p===0) msg = "Starting compilation...";
    if(p===1) msg = "Done!";
    BIRD3.log.update("WebPack => [%s%%]: %s", p.toFixed(2)*100, msg);
});
// Try to press down further
var dedupe = new webpack.optimize.DedupePlugin();

// Banner
var bannerPlugin = new webpack.BannerPlugin((function(){
    var templates = path.join(__dirname, "../Templates");
    var filename = path.join(templates, "banner.ejs");
    return require("ejs").render(
        fs.readFileSync(filename, "utf8"),
        {
            version: config.version
        },{
            filename: filename,
            rmWhitespace: false
        }
    );
})(), {raw: true, entryOnly: false});

// Clear plugin
var clear = new cleanPlugin(["cdn/app"], config.base);

// PurifyCSS
var purifyPlugin = new purify({
    // Path where everything starts...
    basePath: config.base,
    // These extensions should be searched in the dep files.
    scanForExts: ["oj","ejs","html","md"],
    // The paths/globs to check, path.join()'ed with base.
    paths: [
        // Laravel views
        "app/App/Resources/Views/*/*.php",
        "app/App/Resources/Views/*.php",
        "app/App/Modules/*/Resources/Views/*/*.php",
        "app/App/Modules/*/Resources/Views/*.php",
        "app/Extensions/*/Views/*.php",
        "app/Frontend/Design/Layouts/*.php",
        // User
        "app/Foundation/User/Views/*.php",
        "app/Foundation/User/Views/*/*.php",
        // Chat
        //"app/modules/chat/lib/template/*.html",
        //"app/modules/chat/lib/template/*.php",
        // Xynu
        "app/Frontend/Design/Layouts/*.ejs"
    ]
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
            _jquery, _ojr,
            "BIRD3/App/Entry/Browser/Support/Common",
            "BIRD3/App/Entry/Browser/Support/BootstrapNative",
            "BIRD3/Frontend/Design/panels.js"
        ],
        main: "BIRD3/App/Entry/Browser/Main",
        newMain: "BIRD3/App/Entry/Browser/NewMain",
        upload: "BIRD3/Frontend/Upload",
        xynu: "BIRD3/Frontend/Design/Styles/xynu.scss",
        sizeTest: "BIRD3/App/Entry/Browser/SizeTest"
    },
    output: {
        path: app,
        filename: "[hash]-[name].js",
        chunkFilename: "c_[hash]-[name].js",
        sourceMapFilename: "c_[hash]-[name].map",
        publicPath: "/cdn/app/",
        sourcePrefix: "    "
    },
    //recordsPath: path.join(BIRD3.root, "cache"),
    resolve: {
        extensions: [
            "",             // Support supplied extensions.
            ".js", ".oj",   // JavaScript
            ".json",        // Structured data
            ".css", ".scss",// Styles
            ".ejs", ".md"   // Documents and templates
        ],
        root: [
            config.base,
            path.join(config.base,"app/App/Modules"),
            path.join(config.base,"app/Extensions"),
        ],
        modulesDirectories: [
            'bower_components',
            'node_modules',
            'php_modules',
            'web_modules'
        ],
        alias: {
            // This is sooooo cool!
            BIRD3: path.join(config.base, "app"),
            // Link to the docs as a module.
            "BIRD3.docs": path.join(config.base, "docs/loader.js"),
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
                        "sassport?"+sassq
                    ].join("!")
                )
            },{ // Images
                test: /\.(png|jpg|jpeg|gif|svg)/i,
                loader: [
                    // optimize image
                    "img?minimize=true&optimizationLevel=7",
                    "file",
                ].join("!")
            },{ // Fonts
                test: /\.(eot|woff|woff2|ttf|otf|mp3|wav|ogg|swf)/,
                loader: "file"
            },{ // Markdown
                test: /\.md$/,
                loader: "markdown-it-plus"
            },{ // Embedded JS templates
                test: /\.ejs$/,
                loader: "ejs-compiled?delimiter=?&+rmWhitespaces"
            },{ // Load JSON. How stupid is this...?
                test: /\.json$/,
                loader: "json",
            },{ // OJ -> JS
                test: /\.oj$/,
                loader: "oj?-warn-unknown-ivars&-warn-unknown-selectors"
            },{
                test: /\.js$/,
                loader: "babel",
                exclude: /((runtime|miuri)\.js$|nanoajax|socketcluster|ws|circular-json)/,
            },{ // Webfont generator
                test: /\.font\.(js|json)$/,
                loader: "fontgen"
            }
        ],
        noParse: [
            /\.(min|bundle|pack)\.js$/,
        ]
    },
    babel: {
        presets: ['es2015', 'stage-1'],
        plugins: [
            ["transform-es2015-modules-commonjs", {
                allowTopLevelThis: true
            }]
        ], //"transform-runtime"
        cacheDirectory: path.join(config.base, "cache/babel"),
    },
    oj: {
        runtime: _ojr,
        pre: [ juicy.preprocessor() ],
        options: {
            preprocessor: {
                include_path: [
                    theme,
                    path.join(config.base, "app/Frontend/Frameworks"),
                ],
                defines: {},
            }
        }
    },
    postcss: [mergeRules()],
    "markdown-it": (function(){
        var mdOpt = require("./markdown-it");
        mdOpt.preprocess = function(parser, env, source) {
            var fm = require("front-matter");
            var _ = require("microdash");
            if(fm.test(source)) {
                var fmData = fm(source);
                env = _.extend(env, fmData.attributes);
                return fmData.body;
            } else {
                return source;
            }
        }
        return mdOpt;
    })(),
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
