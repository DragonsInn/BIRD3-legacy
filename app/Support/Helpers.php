<?php

// This file only has helpers, really.

function path_join() {
    return realpath(implode(DIRECTORY_SEPARATOR, func_get_args()));
}

function home_path($path = "") {
    return path_join(app_path(), "../", $path);
}

function resolve($path) {
    return \BIRD3\Support\Resolver::resolve($path);
}
