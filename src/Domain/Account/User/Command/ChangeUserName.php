<?php

declare(strict_types=1);

namespace App\Domain\Account\User\Command;

use Ramsey\Uuid\UuidInterface;

final readonly class ChangeUserName
{
    public function __construct(
        public UuidInterface $userId,
        public string $name,
    ) {
    }
}