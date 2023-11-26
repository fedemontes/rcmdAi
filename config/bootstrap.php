<?php

use DI\ContainerBuilder;
use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$envFiles = [
    __DIR__ . '/.env',
    __DIR__ . '/../.env',
];

if (class_exists(Dotenv::class)) {
    $dotenv = new Dotenv();
    foreach ($envFiles as $envFile) {
        if (file_exists($envFile)) {
            $dotenv->load($envFile);
        }
    }
}

// Build DI container instance
$container = (new ContainerBuilder())
    ->addDefinitions(__DIR__ . '/container.php')
    ->build();

// Create App instance
return $container->get(App::class);
