module.exports = function(config) {
    // Karma config
    var karmaConfig = {
        browsers: ['PhantomJS'],
        frameworks: ['jasmine'],
        reporters: ['spec'],
        files: [
            //{ pattern: runnerScript }
            "tests/*.test.*"
        ],
        preprocessors: {
            "tests/*.test.*": ["webpack"]
        },
        webpack: (function obtainWebPackConfig(){
            // WebPack
            var webpack = require("webpack");
            var root = require("find-root")();
            var webpackConfig = require(root+"/app/System/Config/webpack");

            // Clean the WebPack Config of UglifyJS, if it's there.
            for(var i=0; i<webpackConfig.plugins.length; i++) {
                var plugin = webpackConfig.plugins[i];
                if(
                        plugin instanceof webpack.optimize.UglifyJsPlugin
                        || plugin instanceof webpack.optimize.CommonsChunkPlugin
                ) {
                    delete webpackConfig.plugins[i];
                }
            }

            // Clear modified plugins
            var plugins = webpackConfig.plugins;
            plugins = plugins.filter(function(v){ return v != undefined });
            webpackConfig.plugins = plugins;

            // Enable watch
            // webpackConfig.watch = true;

            // The testrunner is the ONLY entry.
            // It require()s the modules and code it'll need.
            delete webpackConfig.entry;

            return webpackConfig;
        })()
    };

    // Set it.
    config.set(karmaConfig);
};
