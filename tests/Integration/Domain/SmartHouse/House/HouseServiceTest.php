<?php

declare(strict_types=1);

namespace Tests\App\Integration\Domain\SmartHouse\House;

use App\Domain\SmartHouse\House\Command\RecordTemperatureForHouse;
use App\Domain\SmartHouse\House\Command\RegisterNewHouse;
use App\Domain\SmartHouse\House\Event\NewHouseRegistered;
use App\Domain\SmartHouse\House\Event\TemperatureForHouseRecorded;
use App\Domain\SmartHouse\House\HouseService;
use App\Infrastructure\UuidConverter;
use Ecotone\Dbal\DbalConnection;
use Ecotone\EventSourcing\EventSourcingConfiguration;
use Ecotone\Lite\EcotoneLite;
use Ecotone\Lite\Test\FlowTestSupport;
use Ecotone\Messaging\Config\ServiceConfiguration;
use Enqueue\Dbal\DbalConnectionFactory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class HouseServiceTest extends TestCase
{
    private FlowTestSupport $ecotoneLite;

    public function setUp(): void
    {
        $this->ecotoneLite = EcotoneLite::bootstrapFlowTestingWithEventStore(
            containerOrAvailableServices: [new UuidConverter(), DbalConnectionFactory::class => DbalConnection::fromDsn('pgsql://ecotone:secret@database:5432/ecotone'), new HouseService()],
            configuration: ServiceConfiguration::createWithDefaults()
                ->withExtensionObjects([
                    EventSourcingConfiguration::createWithDefaults()
                        // @Important - concurrent access is more than expected. This stream strategy does not put constraints on concurrent access (no aggregate version)
                        ->withPersistenceStrategyFor('house', 'simple')
                ])
                ->withNamespaces(['App\Domain\SmartHouse\House', 'App\Infrastructure']),
            runForProductionEventStore: true,
        );
    }

    #[Test]
    public function it_registers_new_house_and_temperature(): void
    {
        $houseId = Uuid::uuid4();

        $this->assertNotEmpty(
            $this->ecotoneLite
                ->sendCommand(new RegisterNewHouse($houseId, "Old Street"))
                ->sendCommand(new RecordTemperatureForHouse($houseId, 10))
                ->sendCommand(new RecordTemperatureForHouse($houseId, 10))
                ->sendCommand(new RecordTemperatureForHouse($houseId, 11))
                ->sendCommand(new RecordTemperatureForHouse($houseId, 14))
                ->sendCommand(new RecordTemperatureForHouse($houseId, 13))
                ->getEventStreamEvents(HouseService::HOUSE_STREAM_NAME)
        );
    }
}