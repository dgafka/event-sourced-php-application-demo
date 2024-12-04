<?php

declare(strict_types=1);

namespace App\Domain\SmartHouse\House;

use App\Domain\SmartHouse\House\Command\RecordTemperatureForHouse;
use App\Domain\SmartHouse\House\Command\RegisterNewHouse;
use App\Domain\SmartHouse\House\Event\NewHouseRegistered;
use App\Domain\SmartHouse\House\Event\TemperatureForHouseRecorded;
use Ecotone\EventSourcing\EventStore;
use Ecotone\Modelling\Attribute\CommandHandler;

final readonly class HouseService
{
    public const string HOUSE_STREAM_NAME = 'house';

    #[CommandHandler]
    public function registerNewHouse(RegisterNewHouse $command, EventStore $eventStore): void
    {
        $eventStore->appendTo(
            self::HOUSE_STREAM_NAME,
            [new NewHouseRegistered($command->houseId, $command->address)]
        );
    }

    #[CommandHandler]
    public function recordTemperatureChange(RecordTemperatureForHouse $command, EventStore $eventStore): void
    {
        $eventStore->appendTo(self::HOUSE_STREAM_NAME, [
            new TemperatureForHouseRecorded($command->house, $command->temperature)
        ]);
    }
}