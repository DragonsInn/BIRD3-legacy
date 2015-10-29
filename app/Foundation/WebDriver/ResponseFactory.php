<?php namespace BIRD3\Foundation\WebDriver;

// Laravel
use Illuminate\Routing\ResponseFactory as ResponseFactoryBase;

// ... uses ...
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\JsonResponse;

// ... but SHOULD use ...
use BIRD3\Foundation\WebDriver\Response;

class ResponseFactory extends ResponseFactory {
    // The following ones are simple overrides.

    public function make($content = '', $status = 200, array $headers = []) {
        return new Response($content, $status, $headers);
    }

}
