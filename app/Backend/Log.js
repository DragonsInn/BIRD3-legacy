// Modules
var log = require("npmlog");
var LogFileStream = require("logfilestream");
var _ = require("microdash");
var root = require("find-root")();
var path = require("path");

// Make a logfile stream
var streamOpts = {
    logdir: path.join(root, "log"),
    nameformat: '[BIRD3.]YYYY-MM-DD[.log]'
};

// Setup
// Log levels and colors!
[
    {name: "debug",     n: 0, style: {fg: "magenta"}},
    {name: "silly",     n: 0, style: {fg: "blue"}},
    {name: "verbose",   n: 1, style: {fg: "orange"}},
    {name: "update",    n: 1, style: {fg: "cyan"}},
    {name: "info",      n: 2, style: {fg: "green"}},
    {name: "notice",    n: 3, style: {fg: "yellow"}},
    {name: "warn",      n: 4, style: {fg: "red"}, disp: "WARNING"},
    {name: "error",     n: 5, style: {fg: "red", bold: true}, disp: "ERROR"},
    {name: "crit",      n: 6, style: {fg: "black", bg: "red"}, disp: "CRITICAL"}
].forEach(function(v){
    var disp = v.disp || uc_first(v.name);
    log.addLevel(v.name, v.n, v.style, disp);
});
log.level = "update";
// Heading
// FIXME: Get global app name and use THAT instead.
log.heading = "BIRD3";
// Push to file
log.on("log", function(message){
    var nowStr = (new Date()).toUTCString();
    var parts = [
        nowStr,                             // the current time
        "["+log.disp[message.level]+"]"     // the display version of the prefix,
        +((message.prefix || "")+" ")+":", // Prefix
        message.message,                    // the actual message
        "\n"                                // Just here to trigger a new line.
    ];
    var logStream = LogFileStream(streamOpts);
    logStream.write(parts.join(" ")).close();
});

// Helper
function uc_first(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function BIRD3Log() {
    // Using false as prefix, disables it!
    return BIRD3Log.makeGroup(false);
}

module.exports = _.extend(BIRD3Log, log, {
    makeGroup: function(prefix) {
        var o = {};
        for(var level in log.levels) {
            o[level] = (function(l){
                return function() {
                    var args = Array.prototype.slice.call(arguments);
                    args.unshift(prefix);
                    return log[l].apply(log, args);
                }
            })(level);
        }
        return o;
    }
});
