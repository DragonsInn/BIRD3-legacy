<?php return [
    'custom_paths'   => [
        // These paths are RELATIVE! to the app root defined through the app construct
        'app_path'      => "app",
        'config_path'   => "app/System/Config",
        'database_path' => 'app/System',
        'lang_path'     => 'app/Foundation/Languages',
        'public_path'   => "cdn",
        'storage_path'  => "cache"
    ],
    "aliases" => [
        # Base
        "root" => APP_ROOT,
        "BIRD3" => BIRD3_APP,

        # By namespace
        "app" => BIRD3_APP."/App",
        "backend" => BIRD3_APP."/Backend",
        "ext" => BIRD3_APP."/Extensions",
        "foundation" => BIRD3_APP."/Foundation",
        "frontend" => BIRD3_APP."/Frontend",
        "res" => BIRD3_APP."/Resources",
        "system" => BIRD3_APP."/System",

        # Deep links
        "modules" => BIRD3_APP."/App/Modules",
        "theme" => BIRD3_APP."/Frontend/Design",
        "widgets" => BIRD3_APP."/Frontend/Widgets",
        "cdn" => APP_ROOT."/cdn",
        "logs" => APP_ROOT."/logs",
        "docs" => APP_ROOT."/docs",

        // package managers
        "bower" => APP_ROOT."/bower_components",
        "npm" => APP_ROOT."/node_modules",
        "composer" => APP_ROOT."/php_modules",
        "web" => APP_ROOT."/web_modules"
    ]
];
