# BIRD3, the roleplayer's CMS
This is the CMS behind the Dragon's Inn. I have open-sourced it for easier contribution, and to let people see the guts of it. Developers can use this is as a resource to learn about scalable projects that utilize inter-language communication. In fact, I would go as far and say that people actually get to look at an application that would be best described as "common practice".

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

### Note
BIRD3 is devided in various parts. Read more in the "Structure" section! You do not need the whole thing to only run a specific task.

## Cloning properly and setting up
If you are here to help me fix a problem, then the requirements on you are by far minimal. All you need to do is:

```bash
# Clone the repo
$ git clone --recursive https://github.com/DragonsInn/BIRD3.git

# Install JavaScript resources
$ npm -g install bower # If you dont have bower, yet.
$ bower install
$ npm install

# Install PHP resources
curl -sS https://getcomposer.org/installer | php
php composer.phar install
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

## Structure
BIRD3 is a heavy project and has a pretty big stack of applications. Here, I will briefly go over what is used why and how. Brace yourself, this is not easy.

### Connected services
BIRD3 itself consists of a handful of srvices. Having all launched, the default configuration, on my system, bursts into 32 processes. This is happening due to a variety of child- and worker-processes. This is a rough structure of the BIRD3 structure:

```
BIRD3
    | HTTP Server (Amount of CPU cores == Workers)
        - This is the actual HTTP server.
        - It uses the hprose interface to talk to PHP if needed. This let's us bring up the Yii app.
        - All the files in `cdn/` are served statically and right away.
        - All static assets get proper caching headers, too.
    | PHP/Workerman (CPU cores by 2 == Workers)
        - A hprose based, multi-process PHP server that serves the Yii app.
        - This service is launched through a single NodeJS process. If this one dies, it drags it's child along.
    | WebPack Service (Always 1 worker)
        - This worker is responsible for re-building the public JS and CSS files as required.
        - The latest content hash is put into the Redis server to allow the PHP server to pick it up.
        - The client always gets the newest version available ont he server.
    | SSH Service
        - Still in development, this is a SSH service that you can log into to do stuff.
```

### Everything is sorted into folders
The various bits and pieces that work together are ultimatively sorted properly into folders. Some of them are accessed under a shared condition, some are not.

- `config`: This contains the actual config.
- `app`: The Yii based PHP app. This is ultimatively the actual CMS in it's pure glory.
- `php-lib`: All the various PHP files neccessary for the backend server. They are all used in the PHP service. However, `executor.php` is responsible for making the actual response package, and sends it back to `request_handler.php`.
- `node-lib`: This is the NodeJS backend. All the server code is in here. This folder is not shared with the front-end.
- `web-lib`: This contains the scripts that make up the foundation of the front-end code. From here, JavaScript and CSS is generated using WebPack. It should be noted, that though WebPack, I also get to use "foreign" JavaScript dialects, such as OJ, JSX and friends. The same goes for the style. The style is actually written using SASS aka. SCSS. It is a more dynamic CSS dialect and promises a cute mascot girl and pretty neat syntax and functionality.
- `oj-lib`: This folder is soon to be a container for all the OJ related code - ranging from self-crafted framework code over to important files and library code. The code here might be shared with the back-end int he long-run due to me having made a project that allows to `require()` OJ files just like regular JavaScript files.
- `util`: This folder contains specific configurations, self-crafted tools and things that don't have a specific place to belong to.
- `web_modules`: Some dependencies are not available through NPM, Bower or Composer. Plain, old and classic JavaScript or PHP dependencies live here. Some wrapper scripts to make them work with modern stacks are included. This is the more patchy territory of BIRD3.
- `migrations`: The DB migrations managed through Phinx are here. They are automatically generated through Phinx itself. Do NOT tamper with them at any point, except for when making a new migration. But editing these files without knowing what you are doing, and without having your migration status **before** the file itself is dangerous.

Some additional, but not-so-important folders:
- `cache`: The application cache is here, as well as a few logs.
- `logs`: The logs produced through Winston.
- `cdn`: Any public thing is here. Most notable is `cdn/app`, which is the public resource for the generated JS and CSS and their belongings.
- `themes`: This contains the theme. The basic CSS, some patchwork and the layout code. Not too fancy, but it contains the files to finalize the design. Changes to this folder are super rare, actually.
- `misc`: Random stuff.

### Needing what for what
- For building CSS/JS:
    - NodeJS, WebPack
    - If you have no PHP, strip the reference to the `.ws.php` file from `web-lib/main.oj`.
- For running the app
    - PHP, NodeJS, Redis, MySQL and a configuration.
- For running separate workers:
    - Find them in `node-lib`. Be aware that they were intended to be ran via PowerHouse or SocketCluster.

## More information
There are more infos in the Wiki section, thus more will be posted on the development site at a later point. Contributors should contact me via either of the details [given on my website](http://ingwie.me).
