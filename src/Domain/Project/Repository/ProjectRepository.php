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
        return (int)$this->queryFactory->newInsert('Projects', $this->toRow($Project))
            ->execute()
            ->lastInsertId();
    }

    public function getProjectById(int $ProjectId): array
    {
        $query = $this->queryFactory->newSelect('Projects');
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

        $this->queryFactory->newUpdate('Projects', $row)
            ->where(['id' => $ProjectId])
            ->execute();
    }

    public function existsProjectId(int $ProjectId): bool
    {
        $query = $this->queryFactory->newSelect('Projects');
        $query->select('id')->where(['id' => $ProjectId]);

        return (bool)$query->execute()->fetch('assoc');
    }

    public function deleteProjectById(int $ProjectId): void
    {
        $this->queryFactory->newDelete('Projects')
            ->where(['id' => $ProjectId])
            ->execute();
    }

    private function toRow(array $Project): array
    {
        return [
            'id' => $Project['id'],
            'email' => $Project['email'],
            'descripcion' => $Project['descripcion'],
            'arguments' => $Project['arguments'],


        ];
    }
}
