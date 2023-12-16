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


final class AuthLoginAction
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
    $message = "Unathorized access"; 
    
	if ( $decode )
	{
		if ( strcmp($result->user,$decode->user) == 0 && strcmp($result->email,$decode->email) == 0 && strcmp($result->alta,$decode->alta) == 0 )
	          {

                    $message = "Token validated. ";
                    $cookie =  hash('sha512',$_ENV['SECRET_KEY']. $result->apikey . $decode->email . time(), false) ; 
                    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
                    setcookie('rcmdAi-', $cookie, time()+60*60*24, '/', $domain, false);
                    $error = 200;
                    $this->session->set('sha1', $cookie);
                    $this->session->set('email', $result->email);
                    $_SESSION['sha1'] = $cookie;
                    $_SESSION['email'] = $result->email;

                    $this->session->save();
	 	  } 

	}
		 

	$message = array("code" => $error, "message" => $message);
        return [
            'apikeys' => $message 
        ];
    }
}
