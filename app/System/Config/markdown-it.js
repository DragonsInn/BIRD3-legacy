var mdTitle = require("markdown-it-title");
var mdToc = require("markdown-it-table-of-contents");
var mdArrow = require("markdown-it-smartarrows");
var mdAnchor = require("markdown-it-anchor");
var mdAttrs = require("markdown-it-attrs");

module.exports = {
    preset: "default",
    typographer: false,
    html: true,
    linkify: true,
    breaks: true,
    use: [
        mdTitle,
        mdArrow, mdAnchor,
        mdAttrs, mdToc
    ]
}
