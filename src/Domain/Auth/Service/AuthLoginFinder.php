<?php

namespace App\Domain\Auth\Service;

use App\Domain\Auth\Data\AuthLoginResult;

use App\Domain\Auth\Repository\AuthLoginRepository;


final class AuthLoginFinder
{
    private AuthLoginRepository $repository;

    public function __construct(AuthLoginRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAuthLogin($request): AuthLoginResult
    {
        // Input validation
        // ...

        $user = $this->repository->findAuthLogin($request);
        if ( is_array($user) &&  count($user[0]) == 0 )
	   return array();	

        return $this->createResult($user[0]);
    }

    private function createResult(Array $user): AuthLoginResult
    {

            $login = new AuthLoginResult();
	    
            $login->id = $user['id'];
            $login->user = $user['user'];
            $login->email = $user['email'];
            $login->apikey = $user['apikey'];
            $login->alta = $user['alta'];
         

        return $login;
    }
}
