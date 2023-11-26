<?php

namespace App\Domain\Project\Service;

use App\Domain\Project\Data\ProjectFinderItem;
use App\Domain\Project\Data\ProjectFinderResult;
use App\Domain\Project\Repository\ProjectFinderRepository;

final class ProjectFinder
{
    private ProjectFinderRepository $repository;

    public function __construct(ProjectFinderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findProjects(): ProjectFinderResult
    {
        // Input validation
        // ...

        $Projects = $this->repository->findProjects();

        return $this->createResult($Projects);
    }

    private function createResult(array $ProjectRows): ProjectFinderResult
    {
        $result = new ProjectFinderResult();

        foreach ($ProjectRows as $ProjectRow) {
            $Project = new ProjectFinderItem();
            $Project->id = $ProjectRow['id'];
            $Project->user = $ProjectRow['user'];
            $Project->email = $ProjectRow['email'];
            $Project->apikey = $ProjectRow['apikey'];
            $Project->alta = $ProjectRow['alta'];
         
            $result->Projects[] = $Project;
        }

        return $result;
    }
}
