<?php namespace BIRD3\Foundation\WebDriver\Providers;

// Laravel
use Illuminate\Support\ServiceProvider;

// BIRD3
use BIRD3\Foundation\WebDriver\Filters\WebDriverFilter;

class WebDriverServiceProvider extends ServiceProvider {

    public function boot() {
        $r = $this->app["router"];
        $r->after(WebDriverFilter::class);
    }

    public function register() {}

}
