import RPC from "BIRD3/Foundation/RPC";

export default
class BaseProcess {
  constructor(serviceName) {
    this.__rpc = new RPC(serviceName);
  }
}
