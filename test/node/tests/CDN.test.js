import {config} from "BIRD3/Support/GlobalConfig";
import CDN from "BIRD3/Support/CDN";

describe("CDN", function(){
    it("can be turned on/off.", function(){
        expect(config.CDN.enable).toBeDefined();
    });
    xit("does not append a slash.", function(){
        // Use local CDN
        var url_local = CDN("foo/bar.txt");
        // Use remote
        var url_remote = CDN("foo/bar.txt");

        expect(url_local.substr(-1) != "/").toBeTrue();
        expect(url_remote.substr(-1) != "/").toBeTrue();
    });
});
