var winston = require("winston");
module.exports = function(base) {
    var lc = {
        levels: {
            debug: 0,
            silly: 0,
            verbose: 1,
            update: 1,
            info: 2,
            notice: 3,
            warn: 4,
            error: 5
        },
        colors: {
            silly: 'magenta',
            verbose: 'orange',
            debug: 'blue',
            info: 'green',
            data: 'grey',
            warn: 'yellow',
            error: 'red',
            warning: 'red', warn: 'red',
            notice: 'yellow',
            update: "cyan"
        }
    };

    var logger = new (winston.Logger)({
        transports: [
            new (winston.transports.Console)({
                colorize: true,
                timestamp: true
            }),
            new (winston.transports.File)({
                filename: base+'/log/bird3.log',
                json: false,
                maxsize: 50*1024^2
            })
        ]
    });

    // Teach Winston...
    logger.setLevels(lc.levels);
    winston.addColors(lc.colors);

    // Correct the level
    logger.level="update";

    return logger;
}