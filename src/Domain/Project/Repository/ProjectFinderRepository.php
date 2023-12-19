<?php

namespace App\Domain\Project\Repository;


use App\Factory\QueryFactory;
use Odan\Session\SessionManagerInterface;


final class ProjectFinderRepository
{
    private QueryFactory $queryFactory;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    public function findProjects(): array
    {

        $email = $_SESSION['email'];
        $query = $this->queryFactory->newSelect('projects');

        $query->select(
            [
                'id',
                'email',
                'descripcion',
                'arguments',
                
            ]
        )->where(['email' => $email ]);

        // Add more "use case specific" conditions to the query
        // ...

        return $query->execute()->fetchAll('assoc') ?: [];
    }
}
