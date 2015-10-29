<?php namespace BIRD3\Foundation\WebDriver\Contracts;

interface WebDriver {

    // This is super important. Without this, we woudl run into troubble.
    static function InitializeAndRun(array $arguments);

}
