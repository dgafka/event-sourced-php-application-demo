<?php

declare(strict_types=1);

namespace Tests\App\Functional;

use App\Domain\Finance\Deposit\Command\MakeDeposit;
use App\Domain\Finance\MerchantTransaction\Command\CompleteTransaction;
use App\Domain\Finance\MerchantTransaction\Command\StartTransaction;
use App\Domain\Finance\MerchantTransaction\Event\TransactionCompleted;
use App\Domain\Finance\MerchantTransaction\Event\TransactionStarted;
use App\Domain\Account\User\Command\RegisterUser;
use App\Infrastructure\UuidConverter;
use App\Projection\Finance\BalanceProjection;
use App\Projection\Account\UserProjection;
use Ecotone\Lite\EcotoneLite;
use Ecotone\Messaging\Config\ServiceConfiguration;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class BalanceTest extends TestCase
{
    #[Test]
    public function it_adjusts_the_balance(): void
    {
        $ecotoneLite = EcotoneLite::bootstrapFlowTestingWithEventStore(
            containerOrAvailableServices: [new UuidConverter(), new UserProjection(), new BalanceProjection()],
            configuration: ServiceConfiguration::createWithDefaults()->withNamespaces(['App'])
        );

        $userId = Uuid::uuid4();
        $transactionOneId = Uuid::uuid4();
        $transactionTwoId = Uuid::uuid4();

        $this->assertEquals(
            400,
            $ecotoneLite
                ->sendCommand(new RegisterUser($userId, 'John Doe'))
                ->sendCommand(new MakeDeposit(Uuid::uuid4(), $userId, 1000))
                ->sendCommand(new StartTransaction($transactionOneId, $userId, 250))
                ->sendCommand(new CompleteTransaction($transactionOneId))
                ->sendCommand(new StartTransaction($transactionTwoId, $userId, 350))
                ->sendCommand(new CompleteTransaction($transactionTwoId))
                ->sendQueryWithRouting('getBalance', $userId)
        );
    }
}