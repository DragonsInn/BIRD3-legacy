# BIRD3, the role-player's CMS
This is the CMS behind the Dragon's Inn. I have open-sourced it for easier contribution, and to let people see the guts of it. Developers can use this is as a resource to learn about scalable projects that utilize inter-language communication and other things. In fact, I would go as far and say that people actually get to look at an application that would be best described as "common practice" or at least "running with current tech".

BIRD3 runs on UNIX systems **only**. I might make it compatible to Windows at some point, but core-dependencies such as Workerman require UNIX. So if you want to try running BIRD3, use docker. I might provide an image in the future.

## Common practices
### Modularized
The whole BIRD3 source tree is modularized. It even has a few modules inside of it that are to be published out of it too. Like it's web server component.

### Unit testing
BIRD3 is tested in all aspects back and forth. We use Jasmine for Node and Web, Peridot for PHP.

### Code sharing
The namespaces are aligned in a way that allows the code to be shared between the various parts. For instance, the frontend could easily use the same method for pasword generation than the backend. No problem!

### Branching
Heavy changes in the code are branched, minor changes are kept on master. Master is always the topmost development branch.

### Versioning with semver
Although `BIRD 3.0.0-dev.76` may not look like it, but it actually is a semantic version:
    * Major: `3`
    * Minor: `0`
    * Patch: `0`
    * Tag: `dev.76`

### Development and production dependencies are separated
BIRD3 makes use of `dependencies` and `devDependencies` in order to sort it's stuff into what is needed in development, and which is only needed for running.

## Dependencies, when running the whole stack:
- Redis (I use 2.8.15 at the moment)
- MySql >= 5.5
- Nodejs >= v4.0.0
    * npm >= 3 is recommended.
    * WebPack does not seem to like the 4.x.x branch actually. Might try out 5.x.x versions if it causes issues.
- PHP >= 5.5.4
    * Composer
    * Native extensions:
        - pcntl (Install from php source)
        - [hprose](https://github.com/hprose/hprose-pecl)

## Dependencies, when you only want to run an assets build (WebPack)
- Node >= v4.0.0
- NPM >= 3.5.0 (use `npm -g i npm` to update to latest)
- Bower

### Note
BIRD3 is divided in various parts. Read more in the "Structure" section! You do not need the whole thing to only run a specific task.

## Cloning properly and setting up
If you are here to help me fix a problem, then the requirements on you are very minimal. All you need to do is:

```bash
# Clone the repo
$ git clone --recursive https://github.com/DragonsInn/BIRD3.git

# Install JavaScript resources
$ npm -g install bower # If you don't have bower, yet.
$ bower install
$ npm install

# Install PHP resources
curl -sS https://getcomposer.org/installer | php # If you dont have Composer yet
php composer.phar install
```

Copy `config/_BIRD3.yml` to `config/BIRD3.yml` and adjust the settings. The values are used by:

- The migration system (Phinx)
- The Web-App itself (Laravel 5.1)
- The automatically generated theme (SCSS)
- The NodeJS and PHP backend

## A rough idea of what BIRD3 uses.
As BIRD3 is a really big project, it doesn't hurt to look at it from a topmost view, to see _what it roughly uses_.

### NodeJS
| Name                                     | Version          |
|------------------------------------------|------------------|
| [SocketCluster](http://socketcluster.io) | v4.x             |
| [Express](http://expressjs.com/)         | v4.x             |
| [Babel](http://babeljs.io/)              | v6.x             |
| [WebPack](http://webpack.github.io/)     | v0.12.x          |
| PowerHouse                               | Selfmade, latest |
| WebDriver                                | Selfmade, latest |
| [OJ](https://github.com/musictheory/oj)  | v1.x             |
| [Uniter](https://github.com/uniter)      | Various          |
| [hprose](http://hprose.com/)             | Various          |

### PHP
| Name                                             | Version          |
|--------------------------------------------------|------------------|
| [Laravel](https://laravel.com/)                  | v5.1.x           |
| [Workerman](https://github.com/walkor/Workerman) | v3.2.x           |
| Facebook SDK                                     | v5 via SP        |
| [Entrust](https://github.com/Zizaco/entrust)     | Via git (master) |
| [Phinx](https://phinx.org/)                      | 0.5.x            |

### Browser
#### JavaScript
| Name                                                           | Version      |
|----------------------------------------------------------------|--------------|
| [Grapnel](http://grapnel.js.org/)                              | v0.6.x       |
| [Bootstrap.Native](https://github.com/thednp/bootstrap.native) | Git (master) |
| ally.js                                                        | 0.0.6        |
| progressbar.js                                                 | v0.8.x       |
| web-socket-js                                                  | v1.x         |

#### S/CSS
| Name                           | Version           |
|--------------------------------|-------------------|
| Bootstrap SCSS                 | v3.3.2            |
| Bootswatch                     | v3.3.2            |
| Normalize CSS                  | v3.x              |
| Bootstrap Accessibility Plugin | v1.x              |
| Ladda                          | v0.9.x            |

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

### Everything is sorted into folders - well, namespaces
The various bits and pieces that work together are ultimatively sorted properly into folders/namespaces. Some of them are accessed under a shared condition, some are not.

- `config`: This contains the actual config.
- `app`: This folder contains the real app logic. It responds to the PHP namespaces.
    - `App`: The Laravel based app, and entrypoints for Servers and Browsers.
    - `Backend`: The "library" for the backend. Http Kernel, Routes, and more.
    - `Frontend`: The design, and other front-end stuff.
    - `Foundation`: Fundamental code, used on both sides. Some Foundation code is actually open-source.
    - `Extensions`: Extensions. Mostly open-source.
    - `Support`: Not very important code, but stuff that makes coding sweet.
    - `System`: Internal config, migrations, seeders, all that stuff.
    - `bootstrap`: Code used to start a process.
- `util`: This folder contains self-crafted tools and things that don't have a specific place to belong to.

Some additional, but not-so-important folders:
- `cache`: The application cache is here, as well as a few logs.
- `log`: The logs produced through Winston.
- `cdn`: Any public thing is here. Most notable is `cdn/app`, which is the public resource for the generated JS and CSS and their belongings.
- `misc`: Random stuff.

## More information
There are more infos in the Wiki section, thus more will be posted on the development site at a later point. Contributors should contact me via either of the details [given on my website](http://ingwie.me).
