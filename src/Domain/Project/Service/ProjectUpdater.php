<?php

namespace App\Domain\Project\Service;

use App\Domain\Project\Repository\ProjectRepository;
use App\Factory\LoggerFactory;
use DomainException;
use Psr\Log\LoggerInterface;

final class ProjectUpdater
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
            ->addFileHandler('Project_updater.log')
            ->createLogger();
    }

    public function updateProject(int $ProjectId, array $data): void
    {
        // Input validation
        $this->validateProjectUpdate($ProjectId, $data);

        // Update the row
        $this->repository->updateProject($ProjectId, $data);

        // Logging
        $this->logger->info(sprintf('Project updated successfully: %s', $ProjectId));
    }

    public function validateProjectUpdate(int $ProjectId, array $data): void
    {
        if (!$this->repository->existsProjectId($ProjectId)) {
            throw new DomainException(sprintf('Project not found: %s', $ProjectId));
        }

        $this->ProjectValidator->validateProject($data);
    }
}
