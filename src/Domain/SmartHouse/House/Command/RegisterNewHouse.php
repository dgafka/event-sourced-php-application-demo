<?php

declare(strict_types=1);

namespace App\Domain\SmartHouse\House\Command;

use Ramsey\Uuid\UuidInterface;

final readonly class RegisterNewHouse
{
    public function __construct(
        public UuidInterface $houseId,
        public string        $address,
    ) {}
}