<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing;
use Symfony\Component\Routing\RouteCollection;

// Load the services configuration
$container = require __DIR__ . '/../config/services.php';

// Set up the request
$request = Request::createFromGlobals();

// Define routes
$routes = require __DIR__ . '/../config/routes.php';

// Create RouteCollection from routes
$routeCollection = new RouteCollection();
foreach ($routes as $name => $route) {
    $routeCollection->add($name, $route);
}

// Set up the context and matcher
$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routeCollection, $context);

// Set up the controller and argument resolvers
$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

// Set up the HTTP kernel
$kernel = new HttpKernel\HttpKernel($matcher, $controllerResolver, $argumentResolver);

try {
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} catch (Routing\Exception\ResourceNotFoundException $e) {
    $response = new Response('Not Found', 404);
    $response->send();
} catch (Exception $e) {
    $response = new Response('An error occurred: ' . $e->getMessage(), 500);
    $response->send();
}
