// Quickfix to get LDT to speak CommonJS.
module.exports = require("exports?TextareaDecorator!LDT/lib/TextareaDecorator");
module.exports.Keybinder = require("exports?Keybinder!LDT/lib/Keybinder");
module.exports.Parser = require("exports?Parser!LDT/lib/Parser");
module.exports.SelectHelper = require("exports?SelectHelper!LDT/lib/SelectHelper");
module.exports.css = require("LDT/lib/TextareaDecorator.css");
