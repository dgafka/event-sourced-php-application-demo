<?php

declare(strict_types=1);

namespace App\Domain\Finance\MerchantTransaction;

use App\Domain\Finance\MerchantTransaction\Command\CancelTransaction;
use App\Domain\Finance\MerchantTransaction\Command\CompleteTransaction;
use App\Domain\Finance\MerchantTransaction\Command\StartTransaction;
use App\Domain\Finance\MerchantTransaction\Event\TransactionCancelled;
use App\Domain\Finance\MerchantTransaction\Event\TransactionCompleted;
use App\Domain\Finance\MerchantTransaction\Event\TransactionStarted;
use Ecotone\EventSourcing\Attribute\AggregateType;
use Ecotone\EventSourcing\Attribute\Stream;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\EventSourcingAggregate;
use Ecotone\Modelling\Attribute\EventSourcingHandler;
use Ecotone\Modelling\Attribute\Identifier;
use Ecotone\Modelling\WithAggregateVersioning;
use Ramsey\Uuid\UuidInterface;

#[Stream('balance-stream')]
#[AggregateType('merchant-transaction-aggregate')]
#[EventSourcingAggregate]
final class MerchantTransaction
{
    use WithAggregateVersioning;

    #[Identifier]
    private UuidInterface $transactionId;

    #[CommandHandler]
    public static function start(StartTransaction $command): array
    {
        return [
            new TransactionStarted(
                $command->transactionId,
                $command->userId,
                $command->amount,
            )
        ];
    }

    #[CommandHandler]
    public function cancel(CancelTransaction $command): array
    {
        return [
            new TransactionCancelled($command->transactionId)
        ];
    }

    #[CommandHandler]
    public function complete(CompleteTransaction $command): array
    {
        return [
            new TransactionCompleted($command->transactionId)
        ];
    }

    #[EventSourcingHandler]
    public function applyTransactionStarted(TransactionStarted $event): void
    {
        $this->transactionId = $event->transactionId;
    }
}