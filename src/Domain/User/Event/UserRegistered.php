<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use Ecotone\Modelling\Attribute\NamedEvent;
use Ramsey\Uuid\UuidInterface;

#[NamedEvent(self::NAME)]
final readonly class UserRegistered
{
    const string NAME = "user.registered";

    public function __construct(
        public UuidInterface $userId,
        public string $name,
    ) {
    }
}