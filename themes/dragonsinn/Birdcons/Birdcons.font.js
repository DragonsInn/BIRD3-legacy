var glob = require("glob").sync;

module.exports = {
    fontName: "Birdcons",
    files: glob("./icons/*.svg"),
    baseClass: "birdcon",
    classPrefix: "" // I.e.: <i class="birdcon arrow"></i>
};
