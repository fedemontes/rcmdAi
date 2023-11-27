<?php

// Define app routes

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ServerRequestInterface;
use Odan\Session\Middleware\SessionStartMiddleware;

 

return function (App $app)  {
    $app->get('/', \App\Action\Home\HomeAction::class)->setName('home');

    // API
    $app->get('/api/v1/auth/login', \App\Action\Auth\AuthLoginAction::class);
    $app->group(
        '/api/v1',
        function (RouteCollectorProxy $app) {

               $app->get('/projects', \App\Action\Project\ProjectFinderAction::class);
               $app->get('/projects/all}', \App\Action\Project\ProjectReaderAction::class);

	 }
    );
};
