<?php namespace BIRD3\Support;

use Ikimea\Browser\Browser;
use Hprose;
use Request;

class BIRD3Helper {
    public function version() {
        $semver = Hprose::get("version");
        return "BIRD@$semver";
    }
    public function userBrowser() {
        $browser = new Browser(Request::server("HTTP_USER_AGENT"));
        $name = $browser->getBrowser();
        $version = $browser->getVersion();
        $os = $browser->getPlatform();
        return "$name@$version ($os)";
    }
}
