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

               $app->get('/Projects', \App\Action\Project\ProjectFinderAction::class);
               $app->get('/Projects/{Project_id}', \App\Action\Project\ProjectReaderAction::class);
               $app->put('/Projects/{Project_id}', \App\Action\Project\ProjectUpdaterAction::class);
               $app->delete('/Projects/{Project_id}', \App\Action\Project\ProjectDeleterAction::class);

	 }
    );
};
