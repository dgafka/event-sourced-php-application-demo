<?php

declare(strict_types=1);

namespace App\Domain\SmartHouse\House\Event;

use Ramsey\Uuid\UuidInterface;

final readonly class NewHouseRegistered
{
    public function __construct(
        public UuidInterface $houseId,
        public string        $address,
    ) {}
}