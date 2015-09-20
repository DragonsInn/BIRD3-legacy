# BIRD3 Coding Style-Guide

The BIRD3 code, at least the one that I write, follows a tiny styleguide. Its simple, readable, and yet nicely small. I am demonstrating all of this on OJ, since it has all the common elements.

## Variables
Variables in BIRD3 are usually named depending on the scope. In a small code, a short name might be used. For isntance:

```javascript
function example(inputArray) {
    var c=new Number();
    for(var i=0; i<inputArray.length; i++) {
        if(inputArray[i]<0) c++;
    }
    return c;
}
```

Obviously, `c` is being used as a **c** ounting variable. Things like these are okay and may happen. In fact, short and otherwise unimportant variables may even be easier to remember this way. However, object properties and properties in returned values should make sense:

```javascript
function Example() {
    // ... snip ...
    return {
        amount: n,
        tries: tr,
        _random: r
    }
}
```

## Blocks and scope definition
- The closing curly bracket in an if-else construct may be on the same line as the `else` keyword as well as the opening bracket. Example:
```javascript
if(...) {
    ... code ...
} else { // <---
    ... code ...
}
```
