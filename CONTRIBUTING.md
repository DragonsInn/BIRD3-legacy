# Contributing to BIRD3

Since I have reached a far state in development, it's safe to detail a few contribution spots:

- Documentation
    * Documentations will be laid into `docs/`.
- Design / Front-end S/CSS
    * Find these in `app/Frontend/Design`.

More information about how to contribute will be added as time progresses.

## Contributing
### ... documentation
Currently, there is not a lot to document - except code. However, the `docs/` folder is forced to follow a specific layout.

```
docs/
  | loader.js               You can ignore this file.
  | example.md              An example markdown file.
  | Topic/file.md           A topic, and a file.
  | Topic/Subtopic/file.md  A topic within an topic, and a file.
```

This would result in a topic list like so:

- (example.md)
- Topic
    - (file.md)
    - Subtopic
        - (file.md)

As you noticed, I put the filenames in parantheses. That is because the actual title is determined from the files themselves.

Typically, the title will be the first heading found within the document. Alternatively, one can supply a front-matter to the document, and cause BIRD3 to use that instead:

```markdown
---
shortTitle: Some Title
---
# Some very long title
Bla bla bla...
```

Normally, the title would be `Some very long title`. But since a front-matter is present, BIRD3 sees that and finds the `shortTitle` property and will use that within the menu instead. This is especially useful when avoiding exploding sidebar titles.

### ... to the design
The whole design code is written using SCSS. It, however, uses WebPack's enhanced `@import` resolver, meaning that it accesses modules like modules, and uses custom include paths in order to shorten path names. So in order to compile the SCSS, you can use a simplified command:

    npm run compile

This will build the front-end; including SCSS.

## Other useful info
During development/contributin work, these NPM scripts will become useful:

`npm run` ...:
- `shell`: Start a PHP shell.
- `artisan`: Use Laravel's artisan command.
- `compile`: Run WebPack.
- `phinx`: Run the DB migration tool.
- `dbtool`: Send a query to the server.
