<?php

declare(strict_types=1);

namespace Tests\App\Unit\Domain;

use App\Domain\MerchantTransaction\Command\CancelTransaction;
use App\Domain\MerchantTransaction\Command\CompleteTransaction;
use App\Domain\MerchantTransaction\Command\StartTransaction;
use App\Domain\MerchantTransaction\Event\TransactionCancelled;
use App\Domain\MerchantTransaction\Event\TransactionCompleted;
use App\Domain\MerchantTransaction\Event\TransactionStarted;
use App\Infrastructure\UuidConverter;
use Ecotone\Lite\EcotoneLite;
use Ecotone\Messaging\Config\ServiceConfiguration;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class MerchantTransactionTest extends TestCase
{
    #[Test]
    public function it_completes_transaction(): void
    {
        $ecotoneLite = EcotoneLite::bootstrapFlowTestingWithEventStore(
            containerOrAvailableServices: [new UuidConverter()],
            configuration: ServiceConfiguration::createWithDefaults()->withNamespaces(['App\Domain\MerchantTransaction', 'App\Infrastructure'])
        );

        $transactionId = Uuid::uuid4();
        $userId = Uuid::uuid4();
        $amount = 100;

        $this->assertEquals(
            [
                new TransactionStarted($transactionId, $userId, $amount),
                new TransactionCompleted($transactionId),
            ],
            $ecotoneLite
                ->sendCommand(new StartTransaction($transactionId, $userId, $amount))
                ->sendCommand(new CompleteTransaction($transactionId))
                ->getRecordedEvents()
        );
    }

    #[Test]
    public function it_cancels_transaction(): void
    {
        $ecotoneLite = EcotoneLite::bootstrapFlowTestingWithEventStore(
            containerOrAvailableServices: [new UuidConverter()],
            configuration: ServiceConfiguration::createWithDefaults()->withNamespaces(['App\Domain\MerchantTransaction', 'App\Infrastructure'])
        );

        $transactionId = Uuid::uuid4();
        $userId = Uuid::uuid4();
        $amount = 100;

        $this->assertEquals(
            [
                new TransactionStarted($transactionId, $userId, $amount),
                new TransactionCancelled($transactionId),
            ],
            $ecotoneLite
                ->sendCommand(new StartTransaction($transactionId, $userId, $amount))
                ->sendCommand(new CancelTransaction($transactionId))
                ->getRecordedEvents()
        );
    }
}