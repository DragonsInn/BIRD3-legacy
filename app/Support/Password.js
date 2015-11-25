var bcrypt = require("bcrypt");

module.exports = {
    hash: function($pwd, cb) {
        bcrypt.genSalt(function(err, salt){
            if(err) return cb(err);
            bcrypt.hash($pwd, salt, function(err, hash){
                if(err) return cb(err);
                // NodeJS natively uses the $2a$ prefix. Change that.
                hash = hash.replace(/^\$2a\$/, "$2y$");
                else cb(null, hash);
            });
        });
    },
    compare: function($pwd, $hash, cb) {
        // Forward the compare call.
        bcrypt.compare($pwd, $hash, cb);
    }
};
