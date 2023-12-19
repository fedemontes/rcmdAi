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
        if ( !isset ($_SESSION['email'] ) ) {
            $message = array("code" => 200, "message" => "Please, start session");
            return $message;
        }
        $Projects = $this->ProjectFinder->findProjects();

        // Transform result and render to json
        return $this->renderer->json($response, $this->transform($Projects));
    }

    public function transform(ProjectFinderResult $result): array
    {
        $Projects = [];
       // Create a standard session handler
	// 

	
        foreach ($result->Projects as $Project) {
            $Projects[] = [
                'id' => $Project->id,
                'email' => $Project->email,
                'descripcion' => $Project->descripcion,
                'arguments' => $Project->arguments,
            ];
        }
	 

        return [
            'projects' => $Projects,
        ];
    }
}
