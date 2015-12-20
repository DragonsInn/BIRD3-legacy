var webpack = require("webpack");
var webpackConfig = require("webpack-config");

module.exports = new webpackConfig()
    .extend(__dirname+"/base.js")
    .merge({
        // Enable source maps and all that stuff.
    });
