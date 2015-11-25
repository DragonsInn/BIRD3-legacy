var b = BIRD3 = {
    baseUrl: <?=$escYiiUrl?>,
    cdnUrl: <?=$escCdnApp?>,
    webpackHash: '<?=$hash?>',
    module: '<?=$module?>',
    hash: function(f){
        return [
            b.cdnUrl, b.webpackHash
        ].join("/")+"-"+f;
    },
    useBottomPanel: <?=$use?>,
};

// Following code contains a modified copy of: https://github.com/EvanHahn/ScriptInclude
b.include = function(target, cb) {
  // save references to save a few bytes
	var doc = document;
    if(typeof target == "string") target = [target];
	var toLoad = target.length; // load this many scripts
    var hasCallback = (typeof cb == "function");
	function onScriptLoaded() {
        var readyState = (function(){return this.readyState;})(); // we test for "complete" or "loaded" if on IE
        if (!readyState || /ded|te/.test(readyState)) {
            toLoad--;
            if (!toLoad && hasCallback) {
                cb();
            }
        }
    }
	var script, skey;
	for (var i = 0; i < toLoad; i ++) {
        var type = target[i].split(".").pop();
        if(type=="js") {
            script = doc.createElement('script');
            skey = "src";
        } else if(type=="css") {
            script = doc.createElement('link');
            script.rel = "stylesheet";
            script.media = "all";
            skey = "href";
        }
		script[skey] = target[i];
		script.onload = script.onerror = script.onreadystatechange = onScriptLoaded;
		(
			doc.head ||
			doc.getElementsByTagName('head')[0]
		).appendChild(script);
	}
};

// Modularize
b.modules = {
    lib: b.hash('libwebpack.js'),
    main: b.hash('main.js'),
    chat: b.hash('chat.js'),
    compatibility: b.hash('compatibility.js'),
    upload: [b.hash('upload.js'), b.hash('upload.css')]
};

b.load = function(m, cb){
    console.log('Loading',m);
    b.include.call(b.include, b.modules[m], cb);
};

// DOMReady stuff
b._readyFuncs = [];
b.ready = function(cb) {
    if(typeof cb != "undefined") {
        b._readyFuncs.push(cb);
    } else {
        for(var i=0; i<b._readyFuncs.length; i++) {
            setTimeout(b._readyFuncs[i],0);
        }
    }
};

// Load the library
b.load("lib", function(){
    console.log('BIRD3 runtime initialized');

    // Make sure WebPack's webpackJsonp is available before anything.
    var loadMain = function(){
        if(typeof window["webpackJsonp"] == "undefined") {
            setTimeout(loadMain, 100);
        } else {
            b.load(b.module, function(){
                b.ready();
            });
        }
    };
    setTimeout(loadMain, 100);
});
