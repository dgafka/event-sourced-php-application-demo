<?php

declare(strict_types=1);

namespace App\Domain\Account\User\Event;

use Ecotone\Modelling\Attribute\NamedEvent;
use Ramsey\Uuid\UuidInterface;

#[NamedEvent(self::NAME)]
final readonly class UserNameChanged
{
    const string NAME = "user.name_changed";

    public function __construct(
        public UuidInterface $userId,
        public string $name,
    ) {
    }
}