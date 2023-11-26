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

               $app->get('/customers', \App\Action\Customer\CustomerFinderAction::class);
               $app->get('/customers/{customer_id}', \App\Action\Customer\CustomerReaderAction::class);
               $app->put('/customers/{customer_id}', \App\Action\Customer\CustomerUpdaterAction::class);
               $app->delete('/customers/{customer_id}', \App\Action\Customer\CustomerDeleterAction::class);

	 }
    );
};
