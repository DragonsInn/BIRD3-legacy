// Fancy buttons
var ladda = require("ladda/js/ladda");
require("BIRD3/Frontend/Design/Styles/ladda.scss");

// Upload
var filedrop = window.fd = require("legacy!filedrop").fd;
filedrop.logging=false;
require("filedrop/filedrop.css");

// FIXME: Crop images that are larger than 150x150. For now, no-go.
//var cropper = require("image-cropper");

var PE = {
    UPLOAD_ERR_OK: 0,
    UPLOAD_ERR_INI_SIZE: 1,
    UPLOAD_ERR_FORM_SIZE: 2,
    UPLOAD_ERR_PARTIAL: 3,
    UPLOAD_ERR_NO_FILE: 4,
    UPLOAD_ERR_NO_TMP_DIR: 6,
    UPLOAD_ERR_CANT_WRITE: 7,
    UPLOAD_ERR_EXTENSION: 8,
    // BIRD3 specific
    UPLOAD_IMG_CANT_RESIZE: -1,
    UPLOAD_IMG_UNSUPPORTED: -2,
    UPLOAD_FILETYPE_MISMATCH: -3,
    UPLOAD_ERROR_SAVE: -4
};

// Refferences to buttons
var $button = document.getElementById("upload_trigger");
var $ladda = ladda.create($button);
var $input = document.getElementById("upload_holder");
var $fd = document.getElementById("filedrop");

// Zoooooooone o.o
var zone = window.zone = new filedrop.FileDrop($fd, {
    iframe: {
        url: location.href,
        fileParam: "image"
    },
    //input: $input,
    multiple: false
});

// Set up an additional handler for the button, cause the button is a derp.
var $fdInput = zone.findInputRecursive(zone.zone);
$button.click(function(e){
    $($fdInput).trigger("click");
});
$("#ladda_label").click(function(e){
    $($fdInput).trigger("click");
});

// The actual zoning begins here.
zone.event("send", function(files){
    files.each(function(file){
        file.event("xhrSetup", function(xhr){
            xhr.setRequestHeader("x-file-input", zone.opt.iframe.fileParam);
        });
        file.event("progress",function(sent, total, xhr, e){
            if(typeof sent=="undefined" && typeof total=="undefined") {
                return;
            }
            $ladda.setProgress( Number(sent*100/total).toFixed(2) );
        });
        file.event("done", function(xhr, e){
            try {
                var o = JSON.parse(xhr.response);
                if(o.error) {
                    return filedrop.callAllOfObject(file, "error", [e, xhr]);
                } else {
                    // Success!
                    //console.log(xhr)
                    $ladda.stop();
                    alert("Uploaded was successful!");
                    document.getElementById("avvie_thumb").src = o.url;
                }
            } catch(e) {
                return filedrop.callAllOfObject(file, "error", [e, xhr]);
            }
        });
        file.event("error", function(e, xhr){
            // The generic error handler.
            $ladda.stop();
            if(xhr && xhr.response) {
                try {
                    var o = JSON.parse(xhr.response);
                    var msg = "";
                    switch(o.code) {
                        case PE.UPLOAD_ERR_INI_SIZE:
                        case PE.UPLOAD_ERR_FORM_SIZE:
                            msg = "The file exceeded the limit in filesize that can be uploaded.";
                            break;

                        case PE.UPLOAD_ERR_PARTIAL:
                        case PE.UPLOAD_ERR_NO_FILE:
                            msg = "The file was not uploaded entirely. Please try again.";
                            break;

                        case PE.UPLOAD_ERR_NO_TMP_DIR:
                        case PE.UPLOAD_ERR_CANT_WRITE:
                        case PE.UPLOAD_ERROR_SAVE:
                            msg = "A server error has occured (unable to save uploaded file).";
                            break;

                        case PE.UPLOAD_ERR_EXTENSION:
                            msg = "This file's extension is not allowed.";
                            break;

                        // BIRD3 specific
                        case PE.UPLOAD_IMG_CANT_RESIZE:
                            msg = [
                                "Unfortunately, we can not resize your image.",
                                "Please upload a smaller version."
                            ].join(" ");
                            break;

                        case PE.UPLOAD_IMG_UNSUPPORTED:
                        case PE.UPLOAD_FILETYPE_MISMATCH:
                            msg = "This filetype is not supported. Please upload a valid file.";
                            break;
                    }
                    alert(msg);
                } catch(e) {
                    // It's a plain text error.
                    alert(e.replace("\n","<br/>").replace(" ","&nbsp;"));
                }
            } else if(e instanceof Error) {
                alert("<pre>"+(e.message || e.stack)+"</pre>");
            }
        });
        $ladda.start();
        file.sendTo(location.href);
    });
});
