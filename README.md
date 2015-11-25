# BIRD3, the roleplayer's CMS
This is the CMS behind the Dragon's Inn. I have open-sourced it for easier contribution, and to let people see the guts of it. Developers can use this is as a resource to learn about scalable projects that utilize inter-language communication. In fact, I would go as far and say that people actually get to look at an application that would be best described as "common practice".

## Depdencies, when running the whole stack:
- Redis (I use 2.8.15 at the moment)
- MySql >= 5.5
- Nodejs >= v4.0.0
    * npm >= 3
- PHP >= 5.5.4
    * Composer
    * Options:
    * Native extensions:
        - pcntl (Install from php source)
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

## Structure
BIRD3 is a heavy project and has a pretty big stack of software underneath. Here, I will briefly go over what is used why and how. Brace yourself, this is not easy.

### Connected services
BIRD3 itself consists of a handful of services. Having all launched, the default configuration, on my system, bursts into 32 processes. This is happening due to a variety of child- and worker-processes. This is a rough structure of the BIRD3 structure:

```
BIRD3
    | SocketCluster/HTTP (Amount of CPU cores == Workers)
        - This is the actual HTTP server.
        - Uses the hprose interface to talk to PHP if needed. This let's us bring up the actual app.
        - All the files in `cdn/` are served statically and right away.
        - All static assets get proper caching headers, too.
    | SocketCluster/WebSocket (Same as HTTP)
        - This is the WebSocket, realtime framework used by BIRD3.
        - It runs on the same port as the webserver.
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
- `app`: This folder contains the real app logic. It responds to the PHP namespaces.
    - `App`: The Laravel based app, and entrypoints for Servers and Browsers.
    - `Backend`: The "library" for the backend. Http Kernel, Routes, and more.
    - `Frontend`: The design, and other front-end stuff.
    - `Foundation`: Fundamental code, used on both sides. Some Foundation code is actually open-source.
    - `Extensions`: Extensions. Mostly open-source.
    - `Support`: Not very important code, but stuff that makes coding sweet.
    - `System`: Internal config, migrations, seeders, all that stuff.
- `util`: This folder contains self-crafted tools and things that don't have a specific place to belong to.
- `web_modules`: Some dependencies are not available through NPM, Bower or Composer. Plain, old and classic JavaScript or PHP dependencies live here. Some wrapper scripts to make them work with modern stacks are included. This is the more patchy territory of BIRD3.

Some additional, but not-so-important folders:
- `cache`: The application cache is here, as well as a few logs.
- `log`: The logs produced through Winston.
- `cdn`: Any public thing is here. Most notable is `cdn/app`, which is the public resource for the generated JS and CSS and their belongings.
- `misc`: Random stuff.

### Needing what for what
- For building CSS/JS:
    - NodeJS, WebPack
- For running the app
    - PHP, NodeJS, Redis, MySQL and a configuration.
- For running separate workers:
    - Find them in `app/Backend/Services`. Be aware that they were intended to be ran via PowerHouse or SocketCluster.

## More information
There are more infos in the Wiki section, thus more will be posted on the development site at a later point. Contributors should contact me via either of the details [given on my website](http://ingwie.me).
