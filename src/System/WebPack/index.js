/**
 * @file
 * This simply puts together both WebPack configs, and allows for an easier
 * passing to the WebPack compiler.
 *
 * Note: Webpack builds for node are only neccessary in production! During
 * development, babel-node is used. WebPack is basically just being used to
 * compile all the Babel stuff into proper NodeJS-readable code. Using Babel's
 * preset-env, this only really means translating some basic things, that
 * Node does not know how to handle - like class properties.
 */

import webConfig from "./web";
import nodeConfig from "./node";

export [
  webConfig,
  nodeConfig
];
