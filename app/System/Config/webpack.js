// Find root
var root = require("find-root")();

// core
var path = require("path");
var merge = require("merge");
var a_merge = require("array-merger").merge;
var glob = require("glob");
var fs = require("fs");

// Config
var BIRD3 = require("../../Support/GlobalConfig");
var config = BIRD3.config;
config.maxFileSize = 1024*10;
config.base = BIRD3.root;

// Paths
var cdnHelper = require("../../Support/CDN");
var cdn = cdnHelper("/app/");
var app = path.join(config.base, "cdn/app");
var theme = path.join(config.base, "App/Frontend/");
var cache = path.join(config.base, "cache");

var __debug = global.__debug || process.env["BIRD3_DEBUG"]==true || false;
var _ojr = require.resolve("ojc/src/runtime");

// Webpack: Load plugins
var webpack = require("webpack");
var extractText = require("extract-text-webpack-plugin");
var HashPlugin = require('hash-webpack-plugin');
var cleanPlugin = require('clean-webpack-plugin');
var purify = require("bird3-purifycss-webpack-plugin");
var appCachePlugin = require("appcache-webpack-plugin");

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
    "oo": "o.o"
});
// Generate bundled CSS. (id, fileName)
var extractor = new extractText("style","[hash]-[name].css",{allChunks:true});
// Compress and press down our JS.
var uglify = new webpack.optimize.UglifyJsPlugin(require("./uglifyjs"));
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
    resolveExtensions: [
        // JS:
        ".js", ".oj",
        // Templates:
        ".ejs", ".jsx",
        // Documents:
        ".md"
    ],
    // The paths/globs to check, path.join()'ed with base.
    paths: [
        // Laravel views
        "app/Resources/Views/**/*.php",
        "app/App/Modules/*/Resources/Views/**/*.php",
        "app/Extensions/*/Views/*.php",
        "app/Frontend/Design/Layouts/*.php",
        // User
        "app/Foundation/User/Views/**/*.php",
        // Chat
        //"app/modules/chat/lib/template/*.html",
        //"app/modules/chat/lib/template/*.php",
        // Xynu
        "app/Frontend/Design/Layouts/*.ejs"
    ],
    purifyOptions: {
        info: true,
        minify: true
    }
});

// manifest
var cdnBase = path.join(config.base, "cdn");
var appCache = new appCachePlugin({
    cache: (function AssetGlobber(){
        var files = glob.sync(cdnBase+"/**/*.{png,jp?g,svg,gif,mp3,wav,ogg}");
        // Do not double-include /cdn/app data.
        files = files.map(function(k){
            return cdnHelper(k.replace(cdnBase, ""));
        }).filter(function(k){
            if( !(/^\/app/.test(k)) ) {
                return k;
            }
        });
        return files;
    })(),
    settings: ["fast"],
    exclude: [],
    output: "allyourcachebelongstothe.appcache"
});

// Babel
var babelConf = require("./babel");
babelConf.cacheDirectory = path.join(config.base, "cache/babel");

// Return the config
module.exports = {
    context: config.base,
    cache: true,
    debug: __debug,
    watchDelay: 1000*5,
    recordsPath: path.join(config.base, "cache/webpack.records.json"),
    //devtool: "#source-map",
    entry: {
        main: "BIRD3/App/Entry/Browser/NewMain",
        xynu: "BIRD3/Frontend/Design/Styles/xynu.scss",
        //sizeTest: "BIRD3/App/Entry/Browser/SizeTest"
    },
    output: {
        path: app,
        filename: "[hash]-[name].js",
        chunkFilename: "c_[hash]-[name].js",
        sourceMapFilename: "s_[hash]-[name].map",
        publicPath: cdn,
        sourcePrefix: "    "
    },
    resolve: {
        extensions: [
            "",                 // Support supplied extensions.
            ".js", ".oj",".jsx",// JavaScript
            ".json",            // Structured data
            ".css", ".scss",    // Styles
            ".ejs", ".md"       // Documents and templates
        ],
        root: [
            config.base,
            path.join(config.base,"app/App/Modules"),
            path.join(config.base,"app/Extensions"),
            path.join(config.base,"app/Frontend/Frameworks")
        ],
        modulesDirectories: [
            'bower_components',
            'node_modules',
            'php_modules'
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
            ws: "ws/lib/browser",
            "LDT$": "BIRD3/Support/Wrappers/LDT",
            "behave.js": "behave.js/behave.js",
            "o.o": "BIRD3/Frontend/Frameworks/o.o/main.js",
            "miniMarkdown": "miniMarkdown/miniMarkdown.js"
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
                loader: "ejs-compiled",
                query: {
                    delimiter: "?",
                    compileDebug: false,
                    rmWhitespaces: true
                }
            },{ // Load JSON. How stupid is this...?
                test: /\.json$/,
                loader: "json",
            },{ // OJ -> JS
                test: /\.oj$/,
                // FIXME: babel
                loader: "oj?-warn-unknown-ivars&-warn-unknown-selectors"
            },{ // ES6/7 -> ES5...hopefuly.
                test: /\.js$/,
                loader: "babel",
                exclude: /(node_modules|bower_components|web_modules)/,
            },{ // JSX!
                test: /\.jsx$/,
                loader: "babel",
                query: {
                    plugins: [
                        ["syntax-jsx"],
                        ["transform-react-jsx",{
                            pragma: "oo"
                        }]
                    ]
                },
                exclude: /(node_modules|bower_components|web_modules)/,
            },{ // Webfont generator
                test: /\.font\.(js|json)$/,
                loader: "fontgen"
            }
        ],
        noParse: [
            /\.(min|bundle|pack)\.js$/,
        ]
    },
    babel: babelConf,
    oj: {
        runtime: _ojr,
        pre: [ juicy.preprocessor() ],
        options: {
            preprocessor: {
                include_path: [
                    theme,
                    path.join(config.base, "app/Frontend/Frameworks"),
                    path.join(config.base, "app")
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
        assetsp, appCache,
        // Cosmetics
        clear, bannerPlugin,
        // Chunking
        dedupe,
        // Module enhancements
        defines, provider, bowerProvider,
        // CSS
        extractor, purifyPlugin,
        // JavaScript
        uglify
    ]
};
