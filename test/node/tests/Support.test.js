import {expect} from "chai";

import BIRD3 from "BIRD3/Support/GlobalConfig";

describe("Support", function(){

    describe("GlobalConfig",function(){
        it("autoloads the configuration", function(){
            expect(BIRD3.config).to.be.a("object");
        });
    });

});
