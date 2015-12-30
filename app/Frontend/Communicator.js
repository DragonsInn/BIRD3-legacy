import foyer from "foyer";

// Track if a Communicator is made.
var initialized = false;
var inst = null;

class Communicator {
    constructor(cb) {
        initialized = true;
        inst = this;
        require.ensure([
            "socketcluster-client/index",
            "avs-rpc"
        ], function(require){
            // Socketcluster and rpc layer.
            var SC = require("socketcluster-client/index");
            var SCRPC = require("avs-rpc").scRpc;

            // Connect
            this.scConn = SC.connect();
            this.rpc = new SCRPC(this.scConn);

            // Connect the basic events.
            this.scConn.on('error', (err) => {
                console.log("Error:", err);
            });
            this.scConn.on('connect', () => {
                // Logging
                this.scConn.on("client_log", (msg) => {
                    console.log("REMOTE>", msg);
                });

                // RPC
                this.scConn.on("rpc_init_data", (methods) => {
                    //console.log("Methods:",methods);
                    this.rpcr = this.rpc.remote.call(this.rpc, methods);
                    // rpcr.foo({baz:"bar"}, console.log); // func(err, res)
                }).emit("rpc_init");
            });

            // Return to client.
            cb(null, this);
        }, "SocketCluster");
    }

    emit(ev, data, rsp) {
        return this.scConn.emit(ev, data, rsp);
    }

    on(ev, cb) {
        return this.scConn.on(ev, data);
    }

    channel(ch) {}
};

export default function CommunicatorFactory(callback) {
    if(initialized) {
        callback(null, inst);
    } else {
        (new Communicator(callback));
    }
}
