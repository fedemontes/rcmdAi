<?php

namespace App\Domain\Project\Repository;

use App\Factory\QueryFactory;
use DomainException;

final class ProjectRepository
{
    private QueryFactory $queryFactory;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    public function insertProject(array $Project): int
    {
        return (int)$this->queryFactory->newInsert('projects', $this->toRow($Project))
            ->execute()
            ->lastInsertId();
    }

    public function getProjectById(int $ProjectId): array
    {
        $query = $this->queryFactory->newSelect('projects');
        $query->select(
            [
                'id',
                'email',
                'descripcion',
                'arguments',
            ]
        );

        $query->where(['id' => $ProjectId]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new DomainException(sprintf('Project not found: %s', $ProjectId));
        }

        return $row;
    }

    public function updateProject(int $ProjectId, array $Project): void
    {
        $row = $this->toRow($Project);

        $this->queryFactory->newUpdate('projects', $row)
            ->where(['id' => $ProjectId])
            ->execute();
    }

    public function existsProjectId(int $ProjectId): bool
    {
        $query = $this->queryFactory->newSelect('projects');
        $query->select('id')->where(['id' => $ProjectId]);

        return (bool)$query->execute()->fetch('assoc');
    }

    public function deleteProjectById(int $ProjectId): void
    {
        $this->queryFactory->newDelete('projects')
            ->where(['id' => $ProjectId])
            ->execute();
    }

    private function toRow(array $Project): array
    {
        return [
            'email' => $_SESSION['email'],
            'descripcion' => $Project['descripcion'],
            'arguments' => json_encode($Project['arguments'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),


        ];
    }
}
