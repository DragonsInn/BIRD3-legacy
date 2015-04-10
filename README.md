# BIRD3, the roleplayer's CMS
This is the CMS behind the Dragon's Inn. I have open-sourced it for easier contribution, and to let people see the guts of it. Developers can use this is as a resource to learn about scalable project that utilize inter-language communication.

## Depdencies:
- Redis (I use 2.8.15 at the moment)
- MySql >= 5.5
- Nodejs >= v0.12
    * Does not seem to work with iojs...
    * npm
- PHP >= 5.4
    * Composer
    * Native extensions:
        - runkit (Use the VCS version, offical does not support newest)
        - redis (PECL)
        - pcntl (Install from php source)
        - [hprose](https://github.com/hprose/hprose-pecl)

## it uses ES6.
To run, use: `node --harmony_proxies app.js`

## More information
There are more infos in the Wiki section, thus more will be posted on the development site at a later point. Contributors should contact me via either of the details [given on my website](http://ingwie.me).
