<?php namespace BIRD3\Support;

class CDN {
    static function link($path = "") {
        $isCdnOn = GlobalConfig::get("CDN.enable");
        if($isCdnOn) {
            # http://drachennetz.com/foo/bar.txt
            $proto = "http";
            $host = GlobalConfig::get("CDN.domain");
            return "${proto}://${host}/${path}";
        } else {
            # /foo/bar.txt
            $base = GlobalConfig::get("CDN.baseUrl");
            return "$base/$path";
        }
    }
}
