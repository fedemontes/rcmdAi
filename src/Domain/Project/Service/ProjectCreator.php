<?php

namespace App\Domain\Project\Service;

use App\Domain\Project\Repository\ProjectRepository;
use App\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;

final class ProjectCreator
{
    private ProjectRepository $repository;

    private ProjectValidator $ProjectValidator;

    private LoggerInterface $logger;

    public function __construct(
        ProjectRepository $repository,
        ProjectValidator $ProjectValidator,
        LoggerFactory $loggerFactory
    ) {
        $this->repository = $repository;
        $this->ProjectValidator = $ProjectValidator;
        $this->logger = $loggerFactory
            ->addFileHandler('Project_creator.log')
            ->createLogger();
    }

    public function createProject(array $data): int
    {
        // Input validation
        $this->ProjectValidator->validateProject($data);

        // Insert Project and get new Project ID
        $ProjectId = $this->repository->insertProject($data);

        // Logging
        $this->logger->info(sprintf('Project created successfully: %s', $ProjectId));

        return $ProjectId;
    }
}
