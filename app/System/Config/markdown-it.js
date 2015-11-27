var mdTitle = require("markdown-it-title");
var mdToc = require("markdown-it-table-of-contents");
var mdArrow = require("markdown-it-smartarrows");
var mdAnchor = require("markdown-it-anchor");
var mdAttrs = require("markdown-it-attrs");

module.exports = {
    preset: "default",
    typographer: true,
    html: true,
    linkify: true,
    use: [
        mdTitle,
        mdArrow, mdAnchor,
        mdAttrs, mdToc
    ]
}
