var spawn = require("child_process").spawn,
    flatten = require("flat").flatten,
    merge = require("merge"),
    path = require("path"),
    base = path.join(__dirname, ".."),
    mi = require("multilevel-ini"),
    config = mi.getSync(base+"/config/BIRD3.ini");

config.base = base;

// prepare the argv data
var argv = new Array(process.argv.length);
for (var i = 0; i < process.argv.length; i++) {
    argv[i] = process.argv[i];
}
argv = argv.splice(2); // node <script>
argv.unshift(path.join(base, "php_modules/bin/phinx"));

// Are we creating?
if(argv[1] == "create") {
    argv.push("--template="+path.join(base, "protected/migration_template.phpt"));
}

console.log("> php "+argv.join(" "));

var phinx = spawn("php", argv, {
    cwd: path.join(base, "config"),
    env: merge(process.env, flatten({PHINX: config})),
    stdio: "inherit"
});

phinx.on("exit", process.exit);
