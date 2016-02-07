var b = BIRD3 = {
    baseUrl: <?=json_encode(config("app.url"))?>,
    cdnUrl: <?=$escCdnApp?>,
    webpackHash: '<?=$hash?>',
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

b.load = function(m, cb){
    console.log('Loading',m);
    b.include.call(b.include, m, cb);
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

b.load(b.hash('main.js'), function(){
    console.log("Loading main.js...");
});

if(window.addEventListener) {
    window.addEventListener("BIRD3.ready", function(){
        b.ready();
    });
} else {
    console.error("What in the world are you using?!");
    window.attachEvent("BIRD3.ready", function(){
        b.ready();
    });
}
