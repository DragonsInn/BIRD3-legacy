<?php namespace BIRD3\Foundation\WebDriver;

use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Kernel as HttpBase;

use BIRD3\Foundation\WebDriver\Request;
use BIRD3\Foundation\WebDriver\Response;

/**
 * @class HproseRequestHandler
 *
 * This class is capable of handling a request dispatched through
 * @class HproseRequestDispatcher.
 *
 * It extends the native Http contract within Laravel, to ensure
 * compatibility.
 */
abstract class Kernel extends HttpBase implements HttpKernel {

    // Holds the hprose arguments. For convenience, same name as in Node.
    private $ctx;

    /**
     * Bootstraps this class.
     * There is currently nothing special to do. Yet.
    */
    public function bootstrap() {
        return parent::bootstrap();
    }

}
