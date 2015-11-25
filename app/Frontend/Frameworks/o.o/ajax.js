var ajax = module.exports = function AJAX(url, settings) {
    // Grab XHR and do something according to settings.
}

ajax._defaults = {
    method: "get",
    json: false,
    data: {},
    onStart: function(xhr){},
    onError: function(e, xhr){},
    onProgress: function(){},
    onEnd: function(code, response, xhr){}
};
