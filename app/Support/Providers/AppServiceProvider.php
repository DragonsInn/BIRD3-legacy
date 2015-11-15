<?php namespace BIRD3\Support\Providers;

use Illuminate\Support\ServiceProvider;
use BIRD3\Support\Resolver;
use BIRD3\Support\BIRD3Helper;
use BIRD3\Backend\Log;

use View;
use App;
use Module;
use FlipFlop;

class AppServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        View::addNamespace("design", app_path("Frontend/Design/Layouts"));
        View::addLocation(app_path("Resources/Views"));
        FlipFlop::setDefaultLayout("design::main");
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

        // BIRD3 helper
        App::singleton(BIRD3Helper::class, function(){
            return new BIRD3Helper;
        });
    }
}
