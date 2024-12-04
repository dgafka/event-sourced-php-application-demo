<?php

declare(strict_types=1);

namespace App\Domain\SmartHouse\House\Command;

use Ramsey\Uuid\UuidInterface;

final readonly class RecordTemperatureForHouse
{
    public function __construct(
        public UuidInterface $house,
        public int  $temperature,
    ) {}
}