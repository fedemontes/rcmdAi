<?php

namespace App\Domain\Auth\Repository;

use App\Factory\QueryFactory;

final class AuthLoginRepository
{
    private QueryFactory $queryFactory;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    public function findAuthLogin($token): array
    {
        $query = $this->queryFactory->newSelect('apikeys');

        $query->select(
            [
                'id',
                'user',
                'email',
                'apikey',
                'alta',
            ]
        );

	$query->where (['apikey = "'.$token.'"']); 

        return $query->execute()->fetchAll('assoc') ?: [];
    }
}
