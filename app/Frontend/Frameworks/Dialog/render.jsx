import guidGenerator from "./guidGenerator";
import Modal from "bootstrap.native/lib/modal-native";
import _ from "microdash";
import oo from "o.o";

// This is the actual modal renderer.
// @param: obj: Contains render data.
// @param: modify: callback to modify the modal.
// @return: Modal
module.exports = function render(obj, modify) {
    var $id = guidGenerator();
    var Body = _.isString(obj.body)
        ? (<div innerHTML={obj.body}/>)[0]
        : obj.body;
    console.trace(Body);
    var modalTemplate = (
        <div
            className={("modal bootstrap-dialog type-"+obj.type+" fade")}
            id={$id} tabindex="-1" role="dialog"
            aria-label={obj.title} aria-hidden="true"
        >
            <div className={("modal-dialog modal-"+obj.size)}>
                <div className="modal-content">
                    <div className="modal-header">
                        <button
                            type="button" className="close"
                            data-dismiss="modal" aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <p className="modal-title" innerHTML={obj.title}/>
                    </div>
                    <div className="modal-body">
                        {(typeof obj.header != "undefined"
                            ? <div innerHTML={obj.header}/>
                            : null
                        )}
                        <div innerHTML={obj.body}/>
                    </div>
                    <div className="modal-footer" innerHTML={obj.footer}/>
                </div>
            </div>
        </div>
    );
    var modalOpts = obj.modalOpts || {};
    // Create the DIV
    oo("body").appendChild(modalTemplate);
    var modal = new Modal(modalTemplate[0], modalOpts);
    modal.close = function() {
        modal._close();
        // This slinks around PhantomJS' disability to .remove(Element)
        // modalTemplate.parentElement.removeChild(modalTemplate);
    }
    if(typeof modify == "function") modify(modal, $id, modalTemplate);
    modal.open();
    return modal;
}
