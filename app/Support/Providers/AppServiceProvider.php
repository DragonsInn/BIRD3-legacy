<?php namespace BIRD3\Support\Providers;

use Illuminate\Support\ServiceProvider;
use BIRD3\Support\Resolver;

use View;
use App;
use Module;

class AppServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        View::addTemplatePath(app_path("Frontend/Design/Layouts"));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        // Set up resolver-related things.
        $resolver = Resolver::getInstance();
        foreach(config("paths.aliases") as $alias=>$path) {
            $resolver->setAliasOfPath($alias, $path);
        }
        $resolver->addResolver(function($alias){
            if(Module::exists($alias)) {
                return resolve("@modules/$alias");
            }
        });
        App::singleton("resolver", function() use($resolver){
            return $resolver;
        });
    }
}
