<?php

namespace App\Action\Project;

use App\Domain\Project\Data\ProjectFinderResult;
use App\Domain\Project\Service\ProjectFinder;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Firebase\JWT\JWT;
use Odan\Session\SessionManagerInterface;


final class ProjectFinderAction
{
    private ProjectFinder $ProjectFinder;
    private SessionManagerInterface $session;
    private JsonRenderer $renderer;

    public function __construct(ProjectFinder $ProjectFinder, JsonRenderer $jsonRenderer, SessionManagerInterface $session)
    {
        $this->ProjectFinder = $ProjectFinder;
        $this->renderer       = $jsonRenderer;
	$this->session        = $session;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Optional: Pass parameters from the request to the service method
        // ...

        $Projects = $this->ProjectFinder->findProjects();

        // Transform result and render to json
        return $this->renderer->json($response, $this->transform($Projects));
    }

    public function transform(ProjectFinderResult $result): array
    {
        $Projects = [];
       // Create a standard session handler
	// 

	/*
        foreach ($result->Projects as $Project) {
            $Projects[] = [
                'id' => $Project->id,
                'user' => $Project->user,
                'email' => $Project->email,
                'apikey' => $Project->apikey,
                'alta' => $Project->alta,
		'decode' => JWT::decode($Project->apikey,"Az5JVxmHYCHCkjre1tb43-dp1LhGgjtHlgS", ["HS256", "HS512", "HS384", "RS256"] ),
		'env' => $_ENV['SECRET_KEY_JWT']
            ];
        }
	 */

        return [
            'projects' => $Projects,
        ];
    }
}
