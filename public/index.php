<?php


use Framework\Http\ResponseSender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiStreamEmitter;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Init

$request = ServerRequestFactory::fromGlobals();

### Action

$path = $request->getUri()->getPath();
$action = null;

if ($path === '/') {
    $action = function (ServerRequestInterface $request) {
        $name = $request->getQueryParams()['name'] ?? 'Guest';
        return new HtmlResponse('Hello ' . $name);
    };


} elseif ($path === '/about') {
    $action = function (ServerRequestInterface $request) {
        return new HtmlResponse('I am a simple site');
    };
} elseif ($path === '/blog') {
    $action = function (ServerRequestInterface $request) {
        new JsonResponse([
            ['id' => 1, 'title' => 'First post'],
            ['id' => 2, 'title' => 'Second post'],
        ]);
    };
} elseif (preg_match('#^/blog/(?P<id>\d+)$#i', $path, $matches)) {
    $request = $request->withAttribute('id', $matches['id']);

    $action = function (ServerRequestInterface $request) {
        $id = $request->getAttribute('id');
        if ($id > 2) {
            return new JsonResponse(['error' => "Page not Found"], 404);
        }
        return new JsonResponse([
            ['id' => $id, 'title' => 'Post #' . $id],
        ]);
    };
}

if ($action) {
    $response = $action($request);
} else {
    $response = new JsonResponse(['error' => "Page not Found"], 404);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'Cobras');

### Sending

$emitter = new SapiStreamEmitter();
$emitter->emit($response);