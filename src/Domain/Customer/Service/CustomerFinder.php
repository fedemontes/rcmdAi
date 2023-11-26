<?php

namespace App\Domain\Customer\Service;

use App\Domain\Customer\Data\CustomerFinderItem;
use App\Domain\Customer\Data\CustomerFinderResult;
use App\Domain\Customer\Repository\CustomerFinderRepository;

final class CustomerFinder
{
    private CustomerFinderRepository $repository;

    public function __construct(CustomerFinderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findCustomers(): CustomerFinderResult
    {
        // Input validation
        // ...

        $customers = $this->repository->findCustomers();

        return $this->createResult($customers);
    }

    private function createResult(array $customerRows): CustomerFinderResult
    {
        $result = new CustomerFinderResult();

        foreach ($customerRows as $customerRow) {
            $customer = new CustomerFinderItem();
            $customer->id = $customerRow['id'];
            $customer->user = $customerRow['user'];
            $customer->email = $customerRow['email'];
            $customer->apikey = $customerRow['apikey'];
            $customer->alta = $customerRow['alta'];
         
            $result->customers[] = $customer;
        }

        return $result;
    }
}
