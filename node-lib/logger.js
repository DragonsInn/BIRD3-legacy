var winston = require("winston");
module.exports = function(base) {
    return new (winston.Logger)({
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
}
