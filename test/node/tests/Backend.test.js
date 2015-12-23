describe("Backend", function(){

    describe("Communicator", function(){
        it("can connect with Redis.");
        it("can send events between clients.");
    });

    describe("HTTP Server", function(){
        it("handles a request to the root (/)");
        it("handles a request to a static resource (/cdn)");
        it("compresses the HTTP body.");
    });

    describe("SocketCluster Server", function(){
        it("accepts connections.");
        it("publishes the RPC methods.");
        it("can authorize a BIRD3 user via JWT.");
        it("can draw the User information from an authenticated instance.");
    });

    describe("WebDriver", function(){
        it("starts without exiting.");
        it("takes requests through hprose.");
    });

});
