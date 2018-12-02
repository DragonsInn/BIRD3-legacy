/**
 * @file: WebPack configuration
 * This file is used to tell WebPack how to compile the front-end
 * client - mainly, the browser client.
 * It will also output a libbird3.js file, which is an API wrapper and
 * allows you to actually use BIRD3 in your own JS app.
 */

// Modules:
const path = require("path");
const fs = require("fs");
const _ = require("lodash");
const glob = require("glob");

// Plugins
const TerserWebpackPlugin = require("terser-webpack-plugin");
const miniCssExtractPlugin = require("mini-css-extract-plugin");
const purgeCss = require("purgecss-webpack-plugin");

// Customs
const customPurgeCssExtractor = require("purgecss-custom-extractor");

// Get the root folder...
const findRoot = require("find-root");
const BIRD3_ROOT = path.realpath(findRoot())

// debug settings?
const DEBUG = (
  process.env.NODE_ENV == "debug"
  || process.env.DEBUG
);

module.exports = {
  mode: DEBUG? "development" : "production",

  entry: (()=>{
    let entries = {
      main: path.join(BIRD3_ROOT, "EntryPoints/Browser")
    }

    _.each(fs.readdir(
      path.join(BIRD3_ROOT, "UI/Components")),
      (value, key, ref) => {
        if(fs.lstat(value).isDirectory()) {
          let key = path.basename(value);
          entries[key] = path.join(value, "index.js")
        }
      }
    );

    return entries;
  })(),

  output: {
    filename: "[name].js",
    path: path.join(BIRD3_ROOT, "data/bird3/public"),
    devtool: "source-map"
  },

  optimization: {
    splitChunks: {
      cacheGroups: {
        styles: {
          name: 'styles',
          test: /\.css$/,
          chunks: 'all',
          enforce: true
        }
      }
    },
  },

  // Module config
  module: {
    rules: [
      {
        test: /\.(js|jsm|jsx)$/,
        use: "babel"
      }, {
        test: /\.scss$/,
        use: miniCssExtractPlugin({
          fallback: "style-loader",
          use: [
            { loader: 'css-loader', options: { importLoaders: 1 } },
            "sass-loader",
            "postcss-loader"
          ]
        })
      }
    ]
  },

  // detailed loader config
  babel: {
    "presets": [
      ["@babel/env", {
        // @TODO: Which IE to target? Targeting IE works on all browsers.
        "targets": { "ie": ">10" }
      }]
    ],
    "plugins": [
      "module:fast-async",
      ["@babel/plugin-proposal-class-properties", {
        "loose": true
      }],
      ["@babel/plugin-transform-react-jsx", {
        "pragma": "h",
        "pragmaFrag": "fragment",
        "throwIfNamespace": false
      }]
    ]
  }
,
  sass: {},
  postcss: {
    plugins: [
      require('tailwindcss')(
        path.join(__dirname, "../TailwindCSS/tailwind.js")
      ),
      // require("autoprefixer")
    ]
  },

  plugins: [
    new TerserWebpackPlugin(),
    new purgeCss({
      paths: glob.sync(
        BIRD3_ROOT + "/**/*",
        { nodir: true }
      )
    }),
    new miniCssExtractPlugin({
      filename: "style.css",
      disable: DEBUG
    })
  ]
}
