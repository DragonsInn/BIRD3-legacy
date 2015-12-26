# o.o - Oh, it happened?

- jQuery is big.
- Alternatives are usually focused on one task.
- Frameworks and today's sites try to force themselves upon a base.

Yawn! It appears that every website is sharing a common 50KB of compressed, only-halfly-used JavaScript. What point does it make? None.

`o.o` is a tiny, vanilla-encouraging JavaScript library - and thats it. It covers most-used functions and tries to be as minimalistic as it can, while remaining very compatible across browsers. Of course, any modern browser will safely support this. But I am talking IE8+. IE6 was Windows XP standard, and since it has been deprecated, Windows 7 being the average OS and Windows 10 featuring Edge - there is no need to support that. But, a surprising amount of people still use IE7...

This library also "wants" to be used with a module loader or bundler like Browserify or WebPack. That is why it adheres to the CommonJS structure.

## Features
- Find one or various elements by a selector.
- Add, remove, toggle and check classes, IDs and properties/attributes.
- Add, set, remove `data-` attributes.
- Connect to events.
- Pick and use an AJAX interface to do some AJAXing. Its minimal, but does the job.
- Use with JSX (Use the `/* @jsx */` directive in Babel).

## The 2-way usage
Usually, libraries want you to include most, if not all their bulk. `o.o` has an eye-candy fix for that. You can pick it up using two methods:

```javascript
// #1 - The whole library.
var oo = require("o.o");
// #2 - A part of the library.
var dom = require("o.o/dom");

// And if you are REALLY picky, there is also #3. The raw o.o function.
var _oo = require("o.o/core");
// You can build your own little lib on top of it. For instance:
var MyMath = function(a,b){ return a + b; }
MyMath.prototype = {
    // Define instance methods here.
    // These methods will be added to an o.o instance.
}
// Add public properties like so:
MyMath.publicProp = 42;
// Now, publish the lib into o.o
_oo.publish({
    math: MyMath
}, MyMath.prototype);

// MyMath is now part of _oo. Extending o.o will also extend Math. It's merged inside-out.

// To just add an instance function:
oo.extend({},{
    squareroot: function(){/* Your magic here. */}
});
```

### About constructors
`o.o` features a very simple constructor method. If you overwrite the prototype's `__init` function, you hit it's constructor. That's it, really.

## Dependencies
The main focus in this library is to stay as tiny as possible and be as efficient as possible. The dependencies used here were mostly discovered by [MicroJS](https://microjs.com)

## Development
This source tree is inside BIRD3, simply because it is yet in conception stage and still under construction.

## Modules
`o.o` is developed with a modular approach in mind.  The `.publish()` method accepts two parameters; the first being an object of public members, and the latter for instance, prototype variables.

The internal modules are all `Function`s. But you can write your implementation however you see fit. But by using a functional approach, you can even use the library by parts instead of by whole! So keep in mind, that `o.o` collects itself off the modules, but can also be plucked apart.

### Built in modules
- Core: The heartpiece of `o.o`. Contains main constructor, utilities and publishing method.
- AJAX: Perform `XMLHttpRequest`s
- DOM: Simple DOM manipulation. Uses `Qwery` as a selector engine, and operates on an array, which is stanced into the instance. The instance also has a `.length` property. This module also provides the main constructor.
- Events: Currently only DOM events, but soon generic events too.
