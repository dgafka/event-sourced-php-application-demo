<?php

declare(strict_types=1);

namespace App\Domain\SmartHouse\House\Event;

use Ramsey\Uuid\UuidInterface;

final readonly class TemperatureForHouseRecorded
{
    public function __construct(
        public UuidInterface $house,
        public int  $temperature,
    ) {}
}