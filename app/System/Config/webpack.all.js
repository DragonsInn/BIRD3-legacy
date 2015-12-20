var webpackConfig = require("webpack-config");
var r = require.resolve;

webpackConfig.environment.setAll({
    env: function() {
        return process.env.WEBPACK_ENV || process.env.NODE_ENV;
    }
});

// Have multiple configs.
module.exports = [
    //new webpackConfig().extend(__dirname+"/WebPack/prod.js"),
    new webpackConfig().extend(r("./WebPack/dev.js"))
];
