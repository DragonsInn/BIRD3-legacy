var mailer = require("nodemailer"),
    smtpPool = require("nodemailer-smtp-pool"),
    mailin = require("mailin"),
    extend = require("util")._extend;
module.exports = function(io, redis) {
    // Mail stuff
    var sender = mailer.createTransport(smtpPool(config.EMail));
    var hdrs = { "X-Powered-by": "BIRD3@"+config.version };
    BIRD3.onRedis("mail.send",function(o){
        sender.sendMail(extend(o, {
            from: config.app.name+" <"+config.app.email+">",
            sender: config.app.email,
            headers: hdrs
        }), function(error, info){
            if(error) {
                BIRD3.error("error", error);
            } else {
                BIRD3.info("msg",info);
            }
        });
    });
}
