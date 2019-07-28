<?php


use Framework\Http\ResponseSender;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiStreamEmitter;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$request = ServerRequestFactory::fromGlobals();


$name = $request->getQueryParams()['name'] ?? 'Guest';


$response = (new HtmlResponse('Hello ' . $name))
    ->withHeader('X-Developer', 'Cobras');

$emitter = new SapiStreamEmitter();
$emitter->emit($response);