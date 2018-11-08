import {
  TcpClient as RPCClient,
  TcpServer as RPCServer
} from "hprose";
import _ from "microdash";
import getPorts from "getports";

// Exceptions
import EnvNotSetException from "BIRD3/Foundation/Exceptions/BIRD3EnvNotSet";
import MissingArgException from "BIRD3/Foundation/Exceptions/MissingArgument";

export default class RPC {
  register(serviceName) {
    if(_.isUndefined(serviceName)) throw new MissingArgException("serviceName");

    const _self = this;

    if(
      !_.isUndefined(process.env.BIRD3_RPC_HOST)
      && !_.isUndefined(process.env.BIRD3_RPC_PORT)
    ) {
      this.uplink = new RPCClient(
        process.env.BIRD3_RPC_HOST,
        process.env.BIRD3_RPC_PORT
      );
      this.uplink.connect(upSock => {
        getPorts(1, freePort => {
          _self.downlink = new RPCServer(freePort):
          _self.uplink.emit("directory.register", {
            name: serviceName,
            port: freePort
          }, (res, e) => {
            if(e) throw e;
            console.log("Connected: " + res)
          });
        });
      });
    } else throw new EnvNotSetException(["RPC_PORT", "RPC_HOST"]);
  }
}
