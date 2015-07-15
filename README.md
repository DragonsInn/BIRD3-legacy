# BIRD3, the roleplayer's CMS
This is the CMS behind the Dragon's Inn. I have open-sourced it for easier contribution, and to let people see the guts of it. Developers can use this is as a resource to learn about scalable project that utilize inter-language communication.

## Depdencies, when running the whole stack:
- Redis (I use 2.8.15 at the moment)
- MySql >= 5.5
- Nodejs >= v0.12
    * Does not seem to work with iojs...
    * npm
- PHP >= 5.4
    * Composer
    * Options:
    * Native extensions:
        - runkit (Use the VCS version, offical does not support newest)
        - redis (PECL)
        - pcntl (Install from php source)
        - sockets
        - [hprose](https://github.com/hprose/hprose-pecl)

## Cloning properly and setting up
If you are here to help me fix a problem, then the requirements on you are by far minimal. All you need to do is:

```bash
$ git clone --recursive https://github.com/DragonsInn/BIRD3.git
$ npm -g install bower # If you dont have bower, yet.
$ bower install
$ npm install
```

Copy `config/BIRD3.ini.example` to `config/BIRD3.ini` and adjust the settings. The values are used by:

- The migration system (Phinx)
- The Web-App itself (Yii)
- The automatically generated theme (WingStyle, `themes/dragonsinn/css/main.ws.php`)
- The NodeJS backend

Depending on what you are helping me with, you won't be needing the full stack - in most cases, not even PHP. When testing WebPack, you'll need to either have PHP installed OR edit `web-lib/main.oj` and comment out a line looking like this:

```javascript
require("dragonsinn/css/main.ws.php");
```

This will eliminate the usage of PHP entirely, since this is the only script that requires it.

## More information
There are more infos in the Wiki section, thus more will be posted on the development site at a later point. Contributors should contact me via either of the details [given on my website](http://ingwie.me).
