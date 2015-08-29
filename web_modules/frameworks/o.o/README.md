# o.o - Oh, it happened?

- jQuery is big.
- Alternatives are usually focused on one task.
- Frameworks and today's sites try to force themselves upon a base.

Yawn! It appears that every website is sharing a common 30KB of compressed, only-halfly-used JavaScript. What point does it make? None.

`o.o` is a tiny, vanilla-encouraging JavaScript library - and thats it. It covers most-used functions and tries to be as minimalistic as it can, while remaining very compatible across browsers. Of course, any modern browser will safely support this. But I am talking IE7+. IE6 was Windows XP standard, and since it has been deprecated, Windows 7 being the average OS and Windows 10 featuring Edge - there is no need to support that. But, a surprising amount of people still use IE7...

This library also "wants" to be used with a module loader or bundler like Browserify or WebPack. That is why it adheres to the CommonJS structure. It also features some classes for usage with [OJ](https://github.com/musictheory/oj).

## Features
- Find one or various elements by a selector.
- Add, remove, toggle and check classes, IDs and properties/attributes.
- Add, set, remove `data-` attributes.
- Connect to events.
- Pick and use an AJAX interface to do some AJAXing. Its minimal, but does the job.

## The 2-way usage
Usually, libraries want you to include most, if not all their bulk. `o.o` has an eye-candy fix for that. You can pick it up using two methods:

```javascript
// #1 - The whole library.
var oo = require("o.o");
// #2 - A part of the library.
var dom = require("o.o/dom");

// And if you are REALLY picky, there is also #3. The raw oo function.
var _oo = require("o.o/core");
// You can build your own little lib ontop of it. For instance:
_oo.publish("math", {
    pi: function() { /* Your magic here. */ }
});
// You can extend the math object, too.
_oo.publish("math", {
    squareroot: function() {/*...*/}
});
// Now: _oo.math -> {pi:..., squareroot:...}
```

### About constructors
You may have noticed, that there is barely a way to really do constructors. Actually,there is. The way it works, is that you publish the constructor and then extend that one's prototype instead. In fact:

```javascript
var oo = require("o.o/core");
oo.mylib = function(){...};
oo.publish("_mylib", { speak: function(){...} );
```

...will add two properties. If you want to access these from an instance you create within the constructor, you'll have to prefix the name in `.publish` with a period. This will cause it to **extend upon the prototype**.

```javascript
var oo = require("o.o/core");
oo.mylib = function(){...};
oo.publish(".mylib", { speak: function(){...} );

var ml = new oo.mylib(...);
ml.speak();
```

## Dependencies
The dependencies for this framework are being kept to the most minimal possible. But no guarantee can be made that one or two slip in.

## Development
This source tree is inside BIRD3, simply because it is yet in conception stage and still under construction.
