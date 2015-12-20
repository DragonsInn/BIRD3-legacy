var webpack = require("webpack");
var webpackConfig = require("webpack-config");

// Compress and press down our JS.
var uglify = new webpack.optimize.UglifyJsPlugin(require("../uglifyjs"));

module.exports = new webpackConfig().extend(__dirname+"/base.js").merge({
    plugins: [uglify]
});
