<?php

use BIRD3\Support\GlobalConfig;
use BIRD3\Support\Resolver;

# System root
$root = Resolver::root(__DIR__);
$system = "$root/app/System";

return [
    "paths" => [
        "migrations" => "$system/Db/Migrations",
        "seeds" => "$system/Db/Seeds"
    ],
    "templates" => [
        "file" => "$system/Templates/migration.phpt"
    ],
    "environments" => [
        "default_migration_table" => "phinxlog",
        "default_database" => "BIRD3",
        "BIRD3" => [
            "adapter" => "mysql",
            "host" => "localhost",
            "name" => GlobalConfig::get("DB.mydb"),
            "user" => GlobalConfig::get("DB.user"),
            "pass" => GlobalConfig::get("DB.pass"),
            "charset" => "utf8"
        ],
    ],
];
