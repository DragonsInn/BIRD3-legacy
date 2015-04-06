var hprose = require("hprose"),
    inspect = require("util").inspect,
    client = new HproseTcpClient("tcp://127.0.0.1:9999"),
    proxy = client.useService();

client.on("error", function() {
    console.log("Error:", arguments);
});

var res = client.invoke("foo", function(){
    console.log("Args:", arguments);
});
console.log(inspect(res));
