<?php
namespace BIRD3\App\Modules\Chat\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class ChatServiceProvider extends ServiceProvider
{
	/**
	 * Register the Chat module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('BIRD3\App\Modules\Chat\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Chat module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('chat', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('chat', realpath(__DIR__.'/../Resources/Views'));
	}
}
