<?php

namespace App\Domain\Project\Service;

use App\Domain\Project\Repository\ProjectRepository;

final class ProjectDeleter
{
    private ProjectRepository $repository;

    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function deleteProject(int $ProjectId): void
    {
        // Input validation
        // ...

        $this->repository->deleteProjectById($ProjectId);
    }
}
