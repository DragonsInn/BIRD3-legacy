<?php
$autoloadPath = "php_modules/autoload.php";
$rootPath = __DIR__;
while(!file_exists("$rootPath/$autoloadPath")) {
    $rootPath = realpath("$rootPath/..");
}
require_once "$rootPath/$autoloadPath";

use Peridot\Console\Environment;
use expect\peridot\ExpectPlugin;

return function($emitter) {
    $eventEmitter->on('peridot.start', function (Environment $environment) {
        $p = __DIR__."/tests";
        $pathArg = $environment
            ->getDefinition()
            ->getArgument('path')
            ->setDefault($p);
    });

    # Plugins
    ExpectPlugin::create()->registerTo($emitter);

    $emitter->on('peridot.configure', function($config) {
        $config->setDsl(__DIR__.'/Dsl/feature.dsl.php');
        $config->setGrep('*.test.php');
        $config->setReporter("feature");
    });

    $emitter->on('peridot.reporters', function($input, $reporters) {
        $reporters->register('feature', 'A feature reporter', 'BIRD3\Test\Php\Dsl\FeatureReporter');
    });
};
