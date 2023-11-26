<?php

namespace App\Action\Project;

use App\Domain\Project\Data\ProjectReaderResult;
use App\Domain\Project\Service\ProjectReader;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ProjectReaderAction
{
    private ProjectReader $ProjectReader;

    private JsonRenderer $renderer;

    public function __construct(ProjectReader $ProjectReader, JsonRenderer $jsonRenderer)
    {
        $this->ProjectReader = $ProjectReader;
        $this->renderer = $jsonRenderer;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // Fetch parameters from the request
        $ProjectId = (int)$args['Project_id'];

        // Invoke the domain and get the result
        $Project = $this->ProjectReader->getProject($ProjectId);

        // Transform result and render to json
        return $this->renderer->json($response, $this->transform($Project));
    }

    private function transform(ProjectReaderResult $Project): array
    {
        return [
            'id' => $Project->id,
            'number' => $Project->number,
            'name' => $Project->name,
            'street' => $Project->street,
            'postal_code' => $Project->postalCode,
            'city' => $Project->city,
            'country' => $Project->country,
            'email' => $Project->email,
        ];
    }
}
