<?php

namespace App\Domain\Auth\Data;

/**
 * DTO.
 */
final class AuthLoginResult
{
    public ?int $id = null;

    public ?string $user = null;

    public ?string $email  = null;

    public ?string $apikey = null;

    public ?string $alta  = null;

}
