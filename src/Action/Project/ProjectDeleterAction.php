<?php

namespace App\Action\Project;

use App\Domain\Project\Service\ProjectDeleter;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ProjectDeleterAction
{
    private ProjectDeleter $ProjectDeleter;

    private JsonRenderer $renderer;

    public function __construct(ProjectDeleter $ProjectDeleter, JsonRenderer $renderer)
    {
        $this->ProjectDeleter = $ProjectDeleter;
        $this->renderer = $renderer;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // Fetch parameters from the request
        $ProjectId = (int)$args['Project_id'];

        // Invoke the domain (service class)
        $this->ProjectDeleter->deleteProject($ProjectId);

        // Render the json response
        return $this->renderer->json($response);
    }
}
