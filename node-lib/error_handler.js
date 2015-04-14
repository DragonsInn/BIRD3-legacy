module.exports = function() {
    BIRD3.on("error", function(e){
        BIRD3.error("BIRD3 going down. Cause: ", e);
        process.exit(1);
    });
    process.on("error", function(e){
        BIRD3.emit("error", e);
    });

    // Signals
    process.on("SIGINT", function(){
        BIRD3.emit("error", "SIGINT");
    });
}
