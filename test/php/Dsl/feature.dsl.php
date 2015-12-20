<?php
use Peridot\Runner\Context;

function Feature($name, $description,  callable $fn) 
{
    $description = 'Feature: ' . $name . $description . "\n";
    Context::getInstance()->addSuite($description, $fn);
}

function Scenario(callable $fn) 
{
    Context::getInstance()->addSuite("Scenario:", $fn);
}

function Given($description, callable $fn)
{
    $test = Context::getInstance()->addTest($description, $fn);
    $test->getScope()->acceptanceDslTitle = "Given";
}

function When($description, callable $fn)
{
    $test = Context::getInstance()->addTest($description, $fn);
    $test->getScope()->acceptanceDslTitle = "When";
}

function Then($description, callable $fn) 
{
    $test = Context::getInstance()->addTest($description, $fn);
    $test->getScope()->acceptanceDslTitle = "Then";
}
