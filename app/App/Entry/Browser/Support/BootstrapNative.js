// Load the equivalent for accessibility
import oo from "o.o";
oo(function(){
    // Run BSN async
    require.ensure(["bootstrap.native"], function(require){
        require("bootstrap.native");
    }, "BootstrapNative");
});
