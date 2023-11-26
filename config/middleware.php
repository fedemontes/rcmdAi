<?php

use App\Middleware\ValidationMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;
use Symfony\Component\Dotenv\Dotenv;
use Odan\Session\Middleware\SessionStartMiddleware;


return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->add(ValidationMiddleware::class);
    $app->addRoutingMiddleware();
    $app->add(BasePathMiddleware::class);
    $app->add(ErrorMiddleware::class);
    $app->add(SessionStartMiddleware::class);
    $app->add(new Tuupola\Middleware\JwtAuthentication([
    "secret" => $_ENV['SECRET_JWT'],
    "error" => function ($response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    
]));
     

};
