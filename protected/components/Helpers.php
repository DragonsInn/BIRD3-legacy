<?php

// This file only has helpers, really.
function joinPaths() {
    $args = func_get_args();
    $paths = array();
    foreach ($args as $arg) {
        if(!empty($arg))
            $paths = array_merge($paths, (array)$arg);
    }

    $paths = array_map(create_function('$p', 'return trim($p, "/");'), $paths);
    $paths = array_filter($paths);
    return join(DIRECTORY_SEPARATOR, $paths);
}

class Markdown {
    public static function parse($input) {
        $p = new ParsedownExtra();
        $md = $p->text($input);
        $hp = new CHtmlPurifier();
        $hp->options = array('URI.AllowedSchemes'=>array(
            'http' => true,
            'https' => true,
        ));
        return $hp->purify($md);
    }
}
