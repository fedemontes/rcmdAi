<?php

namespace App\Domain\Project\Service;

use App\Support\Validation\ValidationException;
use Cake\Validation\Validator;

final class ProjectValidator
{
    public function validateProject(array $data): void
    {
        $validator = new Validator();
        $validator
            ->requirePresence('email', true, 'Input required')
            ->notEmptyString('email', 'Input required')
            ->email('email', false, 'Invalid email address');
            ->requirePresence('descripcion', true, 'Input required')
            ->notEmptyString('descripcion', 'Input required');

        $errors = $validator->validate($data);

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }
}
