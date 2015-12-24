var CDN = require("./GlobalConfig").config.CDN;
var url = require("url");
module.exports = function MakeCDNLink(path) {
    path = path || "/";
    if(CDN.enable) {
        return url.format({
            protocol: "http",
            host:     CDN.domain,
            pathname: path
        });
    } else {
        return CDN.baseUrl+path;
    }
}
