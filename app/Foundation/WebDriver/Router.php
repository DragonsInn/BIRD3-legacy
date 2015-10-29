<?php namespace BIRD3\Foundation\WebDriver;

use Illuminate\Routing\Router as BaseRouter;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

use BIRD3\Foundation\WebDriver\Response;

class Router extends BaseRouter {
    public function prepareResponse($request, $response) {
        if ($response instanceof PsrResponseInterface) {
            $response = (new HttpFoundationFactory)->createResponse($response);
        } elseif (!$response instanceof SymfonyResponse) {
            $response = new WebDriverResponse($response);
        }

        return $response->prepare($request);
    }
}
