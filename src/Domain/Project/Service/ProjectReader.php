<?php

namespace App\Domain\Project\Service;

use App\Domain\Project\Data\ProjectReaderResult;
use App\Domain\Project\Repository\ProjectRepository;

/**
 * Service.
 */
final class ProjectReader
{
    private ProjectRepository $repository;

    /**
     * The constructor.
     *
     * @param ProjectRepository $repository The repository
     */
    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read a Project.
     *
     * @param int $ProjectId The Project id
     *
     * @return ProjectReaderResult The result
     */
    public function getProject(int $ProjectId): ProjectReaderResult
    {
        // Input validation
        // ...

        // Fetch data from the database
        $ProjectRow = $this->repository->getProjectById($ProjectId);

        // Optional: Add or invoke your complex business logic here
        // ...

        // Create domain result
        $result = new ProjectReaderResult();
        $result->id = $ProjectRow['id'];
        $result->number = $ProjectRow['email'];
        $result->name = $ProjectRow['descripcion'];
        $result->street = $ProjectRow['sector'];

        return $result;
    }
}
