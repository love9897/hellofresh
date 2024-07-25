<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use mysqli;

$containerBuilder = new ContainerBuilder();

// Load parameters from configuration files
$databaseConfig = require __DIR__ . '/database.php';

// Add database connection as a service
$containerBuilder->register('database_connection', mysqli::class)
    ->setFactory('mysqli_init')
    ->addMethodCall('real_connect', [
        $databaseConfig['hostname'],
        $databaseConfig['username'],
        $databaseConfig['password'],
        $databaseConfig['database']
    ]);

// Register the RecipeRepository service
$containerBuilder->register('repository.recipe', 'App\Repository\RecipeRepository')
    ->addArgument(new Reference('database_connection'));

// Register the RecipeController service and inject the RecipeRepository service
$containerBuilder->register('controller.recipe', 'App\Controller\RecipeController')
    ->addArgument(new Reference('repository.recipe'));

return $containerBuilder;
