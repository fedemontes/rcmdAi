<?php

namespace App\Action\Project;

use App\Domain\Project\Service\ProjectCreator;
use App\Renderer\JsonRenderer;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ProjectCreatorAction
{
    private JsonRenderer $renderer;

    private ProjectCreator $ProjectCreator;

    public function __construct(ProjectCreator $ProjectCreator, JsonRenderer $renderer)
    {
        $this->ProjectCreator = $ProjectCreator;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $ProjectId = $this->ProjectCreator->createProject($data);

        // Build the HTTP response
        return $this->renderer
            ->json($response, ['Project_id' => $ProjectId])
            ->withStatus(StatusCodeInterface::STATUS_CREATED);
    }
}
