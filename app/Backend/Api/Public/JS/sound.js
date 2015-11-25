// We have no generalized cache, yet. But that should come up soon.
// For the meantime, we shall collect all sounds as-is.
// Its important that we return a CDN url...huh.
/* Soundpack structure:
{
    "rec": {
        "mp3": "sound.mp3",
        "ogg": "sound.ogg",
        "wav": "sound.wav"
    },
    "send": "send",
    "whisper": {...}
    "bot": {...},
    "error": {...},
    "login": {...},
    "logout": {...}
}
Supplying just a string will cause the loader to create an object with all
the filenames with their extensions applied. That means:

    "rec":"usr_msg"

becomes

    "rec": {"mp3":"usr_msg.mp3", "ogg":"usr_msg.ogg", "wav":"usr_msg.wav"}
*/

var baseDir = config.base+"/cdn/sounds",
    glob = require("glob").sync,
    path = require("path"),
    fs = require("fs"),
    names = ["rec", "send", "whisper", "bot", "error", "login", "logout"];

function packNormalize(pObj) {
    for(var k=0; k < names.length; ++k) {
        // Screw you JS. I cant create aliases/pointers/references of primitives....?
        // >:I #nopetrain.
        if(typeof pObj[names[k]] == "string") {
            // Its a pattern, match it. Well its not -really- a pattern...
            var pat = pObj[names[k]];
            var obj = {
                mp3: pat+".mp3",
                ogg: pat+".ogg",
                wav: pat+".wav"
            };
            pObj[names[k]] = obj;
        }
    }
    return pObj;
}

module.exports = {
    getAllPacks: function() {
        var soundPackFiles = glob(baseDir+"/**/main.json");
        var soundPacks={};
        for(var i=0; soundPackFiles.length >= i; ++i) {
            if(typeof soundPackFiles[i] == "undefined") continue;
            var name = path.basename(path.dirname(soundPackFiles[i]));
            soundPacks[name] = require(soundPackFiles[i]);

            // Now validate object
            soundPacks[name] = packNormalize(soundPacks[name]);
        }
        return soundPacks;
    },
    getPack: function(pack) {
        var res={}, found;
        if(fs.existsSync(baseDir+"/"+pack+"/main.json")) {
            var soundPack = require(baseDir+"/"+pack+"/main.json");
            found = true;
        } else {
            var soundPack=null;
            found = false;
        }

        res.pack=(found ? packNormalize(soundPack) : null);
        res.name=pack;
        res.found=found;

        return res;
    }
};
