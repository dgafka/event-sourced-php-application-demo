<?php

declare(strict_types=1);

namespace Tests\App\Unit\Domain;

use App\Domain\Deposit\Command\MakeDeposit;
use App\Domain\Deposit\Event\DepositMade;
use App\Domain\MerchantTransaction\Command\CompleteTransaction;
use App\Domain\MerchantTransaction\Command\StartTransaction;
use App\Domain\MerchantTransaction\Event\TransactionCompleted;
use App\Domain\MerchantTransaction\Event\TransactionStarted;
use App\Infrastructure\UuidConverter;
use Ecotone\Lite\EcotoneLite;
use Ecotone\Messaging\Config\ServiceConfiguration;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class DepositTest extends TestCase
{
    #[Test]
    public function it_makes_deposit(): void
    {
        $ecotoneLite = EcotoneLite::bootstrapFlowTestingWithEventStore(
            containerOrAvailableServices: [new UuidConverter()],
            configuration: ServiceConfiguration::createWithDefaults()->withNamespaces(['App\Domain\Deposit', 'App\Infrastructure'])
        );

        $depositId = Uuid::uuid4();
        $userId = Uuid::uuid4();
        $amount = 100;

        $this->assertEquals(
            [
                new DepositMade($depositId, $userId, $amount),
            ],
            $ecotoneLite
                ->sendCommand(new MakeDeposit($depositId, $userId, $amount))
                ->getRecordedEvents()
        );
    }
}