<?php

namespace App\Action\Customer;

use App\Domain\Customer\Data\CustomerFinderResult;
use App\Domain\Customer\Service\CustomerFinder;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Firebase\JWT\JWT;
use Odan\Session\SessionManagerInterface;


final class CustomerFinderAction
{
    private CustomerFinder $customerFinder;
    private SessionManagerInterface $session;
    private JsonRenderer $renderer;

    public function __construct(CustomerFinder $customerFinder, JsonRenderer $jsonRenderer, SessionManagerInterface $session)
    {
        $this->customerFinder = $customerFinder;
        $this->renderer       = $jsonRenderer;
	$this->session        = $session;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Optional: Pass parameters from the request to the service method
        // ...

        $customers = $this->customerFinder->findCustomers();

        // Transform result and render to json
        return $this->renderer->json($response, $this->transform($customers));
    }

    public function transform(CustomerFinderResult $result): array
    {
        $customers = [];
       // Create a standard session handler
	// 

	/*
        foreach ($result->customers as $customer) {
            $customers[] = [
                'id' => $customer->id,
                'user' => $customer->user,
                'email' => $customer->email,
                'apikey' => $customer->apikey,
                'alta' => $customer->alta,
		'decode' => JWT::decode($customer->apikey,"Az5JVxmHYCHCkjre1tb43-dp1LhGgjtHlgS", ["HS256", "HS512", "HS384", "RS256"] ),
		'env' => $_ENV['SECRET_KEY_JWT']
            ];
        }
	 */

        return [
            'projects' => $customers,
        ];
    }
}
