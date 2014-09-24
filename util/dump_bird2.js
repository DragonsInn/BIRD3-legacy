var conf = {
    user: process.argv[2],
    password: process.argv[3],
    database: process.argv[4]
};
var mysql = require("mysql");
var client= mysql.createConnection(conf);
var fs = require("fs");

client.connect(function(err){
    if(err) throw err;
});

client.query("show tables", function(err, res, fld){
    console.log("-- Tables ("+res.length+"):");
    for(var i=0; i<res.length; i++) {
        var dbname = res[i]["Tables_in_"+conf.database];
        console.log(dbname);
    }
    process.exit(0);
});
