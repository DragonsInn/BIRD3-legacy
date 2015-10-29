<?php namespace BIRD3\Support;

use FlorianWolters\Component\Util\Singleton\SingletonTrait;
use \Closure;

class Resolver {
    use SingletonTrait;

    private $aliases = [];
    private $resolver = [];

    public function setAliasOfPath($name, $path) {
        $this->aliases[$name] = $path;
        return $this;
    }

    public function popPathOfAlias($name) {
        $path = $this->aliases[$name];
        unset($this->aliases[$name]);
        return $path;
    }

    public function getPathOfAlias($name) {
        return $this->aliases[$name];
    }

    public function getAliasOfPath($findPath) {
        foreach($this->aliases as $alias => $path) {
            if($path == $findPath) {
                return $alias;
            }
        }
        return null;
    }

    public static function resolve($path, $root = null) {
        $self = self::getInstance();
        $res = $self->putAlias($path);
        return !is_null($root) ? path_join($root, $res) : $res;
    }

    private function putAlias($str) {
        // Find aliases and replace with path
        // i.e.: @Widgets/Foo -> app/Frontend/Widgets/Foo
        // Allow custom resolvers for specific lookups
        $path = $this->canonicalizePath($str);
        $pathParts = explode("/", $path);
        foreach($pathParts as $i=>$part) {
            if(substr($part,0,1) == "@") {
                // A-hah! A wild alias was been encountered.
                $alias = substr($part, 1);
                if(isset($this->aliases[$alias])) {
                    $pathParts[$i] = $this->aliases[$alias];
                } else {
                    foreach($this->resolver as $cb) {
                        $out = $cb($alias);
                        if($out != null && $out != false) {
                            $pathParts[$i] = $out;
                        }
                    }
                }
            }
        }
        return implode(DIRECTORY_SEPARATOR, $pathParts);
    }

    private function canonicalizePath($in) {
        $out = str_replace("\/","/",$in);
        return $out;
    }

    public function getAliases() {
        return $this->aliases;
    }

    public function addResolver(Closure $cb) {
        $this->resolver[] = $cb;
        return $this;
    }
}
