<?php

namespace App\Action\Project;

use App\Domain\Project\Service\ProjectUpdater;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ProjectUpdaterAction
{
    private ProjectUpdater $ProjectUpdater;

    private JsonRenderer $renderer;

    public function __construct(ProjectUpdater $ProjectUpdater, JsonRenderer $jsonRenderer)
    {
        $this->ProjectUpdater = $ProjectUpdater;
        $this->renderer = $jsonRenderer;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // Extract the form data from the request body
        $ProjectId = (int)$args['Project_id'];
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $this->ProjectUpdater->updateProject($ProjectId, $data);

        // Build the HTTP response
        return $this->renderer->json($response);
    }
}
