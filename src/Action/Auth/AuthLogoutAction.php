<?php

namespace App\Action\Auth;

use App\Domain\Auth\Data\AuthLoginResult;
use App\Domain\Auth\Service\AuthLoginFinder;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Firebase\JWT\JWT;
use Odan\Session\PhpSession;


final class AuthLogoutAction
{
    private AuthLoginFinder $authFinder;

    private JsonRenderer $renderer;

    private PhpSession $session;

    public function __construct(AuthLoginFinder $authFinder, JsonRenderer $jsonRenderer, PhpSession $session )
    {
        $this->authLoginFinder = $authFinder;
        $this->renderer = $jsonRenderer;
        $this->session = $session;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Optional: Pass parameters from the request to the service method
        // ...
       $header = $request->getHeaders();

       $token = str_replace("Bearer ", "", $header['Authorization'][0]);
        	
        $authLogin = $this->authLoginFinder->findAuthLogin($token);

        // Transform result and render to json
        return $this->renderer->json($response, $this->transform($authLogin));
    }

    public function transform(AuthLoginResult $result): array
    {
            $user  = [
                'id' => $result->id,
                'user' => $result->user,
                'email' => $result->email,
                'apikey' => $result->apikey,
                'alta' => $result->alta,
            ];
        
	$decode = JWT::decode($result->apikey,$_ENV['SECRET_JWT'], ["HS256", "HS512", "HS384", "RS256"] );

	
	$error = 401;
    $message = "Unauthorized access"; 

	if ( $decode )
	{
		if ( strcmp($result->user,$decode->user) == 0 && 
             strcmp($result->email,$decode->email) == 0 && 
             strcmp($result->alta,$decode->alta) == 0 )
	          {

                    $this->session->destroy();
                    $message = 'Success logout'
	 	      } 

	}
		 

	$message = array("code" => $error, "message" => $message);
        return [
            'apikeys' => $message 
        ];
    }
}
