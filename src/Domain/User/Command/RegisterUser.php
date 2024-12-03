<?php

declare(strict_types=1);

namespace App\Domain\User\Command;

use Ramsey\Uuid\UuidInterface;

final readonly class RegisterUser
{
    public function __construct(
        public UuidInterface $userId,
        public string $name,
    ) {
    }
}