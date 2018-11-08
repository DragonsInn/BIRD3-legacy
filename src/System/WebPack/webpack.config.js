/**
 * @file: WebPack configuration
 * This file is used to tell WebPack how to compile the front-end
 * client - mainly, the browser client.
 * It will also output a libbird3.js file, which is an API wrapper and
 * allows you to actually use BIRD3 in your own JS app.
 */

// Node:
const path = require("path");

// Plugins
const TerserWebpackPlugin = require("terser-webpack-plugin");

// Get the root folder...
const findRoot = require("find-root");
const BIRD3_ROOT = path.realpath(findRoot())


module.exports = {
  output: {
    output: path.join(BIRD3_ROOT, "data/bird3/public")
  },
  plugins: [
    new TerserWebpackPlugin(/* ... */)
  ]
}
