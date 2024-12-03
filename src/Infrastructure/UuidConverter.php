<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Ecotone\Messaging\Attribute\Converter;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class UuidConverter
{
    #[Converter]
    public static function fromString(string $uuid): UuidInterface
    {
        return Uuid::fromString($uuid);
    }

    #[Converter]
    public static function toString(UuidInterface $uuid): string
    {
        return $uuid->toString();
    }
}