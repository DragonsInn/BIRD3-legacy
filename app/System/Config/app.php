<?php

use \BIRD3\Support\GlobalConfig;

return [

    'debug' => false,
    'url' => implode(":",[
        "http://".GlobalConfig::get("BIRD3.url"),
        GlobalConfig::get("BIRD3.http_port")
    ]),

    // Date and time
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',

    // Encryption key
    'key' => 'nCGwQBdnj9h5x2pyVCeZ80UHhUX8GUpo',
    'cipher' => 'AES-256-CBC',

    // Providers
    'providers' => [
        // Laravel Framework Service Providers...
        Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Routing\ControllerServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        // Application Service Providers...
        BIRD3\Support\Providers\AppServiceProvider::class,
        BIRD3\Support\Providers\EventServiceProvider::class,
        BIRD3\Support\Providers\RouteServiceProvider::class,

        // Extensions
        BIRD3\Extensions\FlipFlop\Providers\FlipFlopServiceProvider::class,
        BIRD3\Foundation\WebDriver\Providers\WebDriverServiceProvider::class,
        BIRD3\Foundation\User\Providers\UserServiceProvider::class,
        BIRD3\Extensions\Editor\Providers\EditorServiceProvider::class,

        // Modules
        Caffeinated\Modules\ModulesServiceProvider::class,
        Spatie\Backup\BackupServiceProvider::class,
        Collective\Html\HtmlServiceProvider::class,
        Pingpong\Widget\WidgetServiceProvider::class,
        Zizaco\Entrust\EntrustServiceProvider::class,
        Thujohn\Twitter\TwitterServiceProvider::class,
        SammyK\LaravelFacebookSdk\LaravelFacebookSdkServiceProvider::class,
    ],

    'aliases' => [
        'App'       => Illuminate\Support\Facades\App::class,
        'Artisan'   => Illuminate\Support\Facades\Artisan::class,
        'Auth'      => Illuminate\Support\Facades\Auth::class,
        'Blade'     => Illuminate\Support\Facades\Blade::class,
        'Bus'       => Illuminate\Support\Facades\Bus::class,
        'Cache'     => Illuminate\Support\Facades\Cache::class,
        'Config'    => Illuminate\Support\Facades\Config::class,
        'Cookie'    => Illuminate\Support\Facades\Cookie::class,
        'Crypt'     => Illuminate\Support\Facades\Crypt::class,
        'DB'        => Illuminate\Support\Facades\DB::class,
        'Eloquent'  => Illuminate\Database\Eloquent\Model::class,
        'Event'     => Illuminate\Support\Facades\Event::class,
        'File'      => Illuminate\Support\Facades\File::class,
        'Gate'      => Illuminate\Support\Facades\Gate::class,
        'Hash'      => Illuminate\Support\Facades\Hash::class,
        'Input'     => Illuminate\Support\Facades\Input::class,
        'Inspiring' => Illuminate\Foundation\Inspiring::class,
        'Lang'      => Illuminate\Support\Facades\Lang::class,
        'Log'       => Illuminate\Support\Facades\Log::class,
        'Mail'      => Illuminate\Support\Facades\Mail::class,
        'Password'  => Illuminate\Support\Facades\Password::class,
        'Queue'     => Illuminate\Support\Facades\Queue::class,
        'Redirect'  => Illuminate\Support\Facades\Redirect::class,
        'Redis'     => Illuminate\Support\Facades\Redis::class,
        'Request'   => Illuminate\Support\Facades\Request::class,
        'Response'  => Illuminate\Support\Facades\Response::class,
        'Route'     => Illuminate\Support\Facades\Route::class,
        'Schema'    => Illuminate\Support\Facades\Schema::class,
        'Session'   => Illuminate\Support\Facades\Session::class,
        'Storage'   => Illuminate\Support\Facades\Storage::class,
        'URL'       => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View'      => Illuminate\Support\Facades\View::class,

        // Modules
        'Module' => Caffeinated\Modules\Facades\Module::class,
        'Resolver' => BIRD3\Support\Facades\Resolver::class,
        'Hprose' => BIRD3\Support\Facades\Hprose::class,
        "BIRD3" => BIRD3\Support\Facades\BIRD3::class,
        "FlipFlop" => BIRD3\Extensions\FlipFlop\Facades\FlipFlop::class,
        "User" => BIRD3\Foundation\User\Entity::class,
        "CDN" => BIRD3\Support\CDN::class,

        // External
        'HTML' => Collective\Html\HtmlFacade::class,
        'Form' => Collective\Html\FormFacade::class,
        'Widget' => Pingpong\Widget\WidgetFacade::class,
        'Entrust' => Zizaco\Entrust\EntrustFacade::class,
        'Twitter' => Thujohn\Twitter\Facades\Twitter::class,
        'Facebook' => SammyK\LaravelFacebookSdk\FacebookFacade::class,
    ],

];
