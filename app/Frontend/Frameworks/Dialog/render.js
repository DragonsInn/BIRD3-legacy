var guidGenerator = require("./guidGenerator");
var template = require("BIRD3/Frontend/Frameworks/Dialog/template.ejs");
var Modal = require("bootstrap.native/lib/modal-native");

// This is the actual modal renderer.
// @param: obj: Contains render data.
// @param: modify: callback to modify the modal.
// @return: Modal
module.exports = function render(obj, modify) {

    var $id = guidGenerator();
    var modalTemplate = template({
        title: obj.title,
        id: $id,
        type: obj.type,
        size: obj.size,
        header: obj.header,
        body: obj.body,
        footer: obj.footer
    });
    var modalOpts = obj.modalOpts || {};
    // Create the DIV
    var elem = document.createElement("div");
    elem.innerHTML = modalTemplate;
    var modalNode = elem.firstChild;
    $("body").append(modalNode);
    var modal = new Modal(modalNode, modalOpts);
    modal.close = function() {
        modal._close();
        $($id).remove();
    }
    if(typeof modify == "function") modify(modal, $id, modalNode);
    modal.open();
    return modal;
}
