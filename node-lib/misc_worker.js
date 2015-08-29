var path = require("path");
var log = require("./logger")(path.join(__dirname, ".."));

// Flash Policy File
// Port: 843
try {
    log.info("Starting Flash Policy File server...");
    require("policyfile").createServer().listen();
} catch(e) {
    log.error("Unable to launch Flash Policy File server!");
    log.error(e);
}
