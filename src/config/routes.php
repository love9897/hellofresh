<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();

$routes->add('recipe_list', new Route('/recipes', [
    '_controller' => 'App\\Controller\\RecipeController::list'
]));

$routes->add('recipe_create', new Route('/recipes', [
    '_controller' => 'App\\Controller\\RecipeController::create',
    'methods' => ['POST']
]));

$routes->add('recipe_read', new Route('/recipes/{id}', [
    '_controller' => 'App\\Controller\\RecipeController::getRecipe'
]));

$routes->add('recipe_update', new Route('/recipes/{id}', [
    '_controller' => 'App\\Controller\\RecipeController::update',
    'methods' => ['PUT', 'PATCH']
]));

$routes->add('recipe_delete', new Route('/recipes/{id}', [
    '_controller' => 'App\\Controller\\RecipeController::delete',
    'methods' => ['DELETE']
]));

$routes->add('recipe_rate', new Route('/recipes/{id}/rating', [
    '_controller' => 'App\\Controller\\RecipeController::rate',
    'methods' => ['POST']
]));

return $routes;
