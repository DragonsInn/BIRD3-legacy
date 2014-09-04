module.exports = function(title, client) {
    client.on("error",function(e){
        log.error(title+" caught an error: "+e);
    });
}
