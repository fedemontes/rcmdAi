<?php

namespace App\Domain\Project\Service;

use App\Support\Validation\ValidationException;
use Cake\Validation\Validator;

final class ProjectValidator
{
    public function validateProject(array $data): void
    {
        $validator = new Validator();
        //$errors = $validator->validate($data);
/*
        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
*/
    }
    
}
