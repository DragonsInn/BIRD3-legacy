var spawn = require("child_process").spawn;
module.exports = {
    // Holds the current amount of PHP instances.
    engines: [],

    // This function will start the PHP interpreter from the exported functions.
    // It shall use the request cacle.
    start: function(){

    },

    // Handles a script file, returns the output.
    // Will check if an engine is available, pushes stuff into it and runs it then.
    run: function(file, request, response, profiling_key) {
        response.writeHead(200);

        var php = spawn("php",[file]);

        // Output handlers
        php.stdout.on("data", function(chunk){
            response.write(chunk.toString("utf-8"));
        });
        php.stderr.on("data", function(chunk){
            log.error(chunk.toString("utf-8"));
        });

        // Cleanup handler
        php.on("exit", function(code, sig){
            response.end();
        });

        log.profile(profiling_key);
    },

    // This will shut down the engine completely.
    end: function() {

    }
};
