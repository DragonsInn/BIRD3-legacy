import SocketCluster from "socketcluster";

const sc = new SocketCluster({
  // App
  appName: 'myapp',
  logLevel: 2,

  // Environment
  environment: 'dev',
  killMasterOnSignal: true,
  instanceId: null,
  secretKey: null,

  // Cryptography and authentication setup.
  // @TODO Use global config and config files for this.
  authKey: null,
  //authPrivateKey: "",
  //authPublicKey: "",
  authDefaultExpiry: 86400, // in seconds
  authAlgorithm: 'HS256',

  // Network
  downgradeToUser: false,
  host: null,
  port: 8000,
  protocol: 'http',
  protocolOptions: null,
  tcpSynBacklog: null,
  path: "/sc",
  wsEngine: 'sc-uws',
  connectTimeout: 10000,
  handshakeTimeout: 10000,
  ackTimeout: 10000,
  ipcAckTimeout: 10000,
  socketUpgradeTimeout: 1000,
  origins: '*:*', // @TODO change to config's URL:Port

  // Socket
  socketChannelLimit: 1000,
  pingInterval: 8000,
  pingTimeout: 20000,

  // Channels
  allowClientPublish: true,

  // Compression
  perMessageDeflate: false,

  // Master + Broker + Server
  processTermTimeout: 10000,
  propagateErrors: true,
  propagateWarnings: true,
  middlewareEmitWarnings: true,

  // Master
  socketRoot: null,
  //socketRoot: path.join(BIRD3.getRoot(), "data/sc")

  // Broker
  brokers: 1,
  brokerController: require.resolve("./Broker"),
  brokerEngine: 'sc-broker-cluster',
  pubSubBatchDuration: null,

  // Worker cluster
  workerClusterController: null,

  // Worker
  workers: 1,
  workerController: require.resolve("./Worker"),
  crashWorkerOnError: true,
  rebootWorkerOnCrash: true,
  //rebootWorkerOnCrash: .environment=="prod"
  killWorkerMemoryThreshold: null,
  rebootOnSignal: true,
  workerStatusInterval: 10000,

  // Misc?
  schedulingPolicy: 'rr',
})
