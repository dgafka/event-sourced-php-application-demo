<?php

declare(strict_types=1);

namespace App\Domain\Finance\Deposit;

use App\Domain\Finance\Deposit\Command\MakeDeposit;
use App\Domain\Finance\Deposit\Event\DepositMade;
use Ecotone\EventSourcing\Attribute\AggregateType;
use Ecotone\EventSourcing\Attribute\Stream;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\EventSourcingAggregate;
use Ecotone\Modelling\Attribute\EventSourcingHandler;
use Ecotone\Modelling\Attribute\Identifier;
use Ecotone\Modelling\WithAggregateVersioning;
use Ramsey\Uuid\UuidInterface;

#[Stream('balance-stream')]
#[AggregateType('deposit-aggregate')]
#[EventSourcingAggregate]
final class Deposit
{
    use WithAggregateVersioning;

    #[Identifier]
    private UuidInterface $depositId;

    #[CommandHandler]
    public static function makeDeposit(MakeDeposit $command): array
    {
        return [
            new DepositMade(
                $command->depositId,
                $command->userId,
                $command->amount
            )
        ];
    }

    #[EventSourcingHandler]
    public function applyDepositMade(DepositMade $event): void
    {
        $this->depositId = $event->depositId;
    }
}