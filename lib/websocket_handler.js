module.exports = function(socket) {
    socket.emit("meep", {"for":"free"});
}
