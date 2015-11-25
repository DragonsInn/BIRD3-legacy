<?php namespace BIRD3\Foundation\WebDriver\Facades;

use Illuminate\Support\Facades\Response as ResponseFacade;

/**
 * @see \Illuminate\Contracts\Routing\ResponseFactory
 */
class Response extends ResponseFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Illuminate\Contracts\Routing\ResponseFactory';
    }
}
