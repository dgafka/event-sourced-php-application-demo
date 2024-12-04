<?php

declare(strict_types=1);

namespace App\Projection\Finance;

use App\Domain\Finance\Deposit\Event\DepositMade;
use App\Domain\Finance\MerchantTransaction\Event\TransactionCancelled;
use App\Domain\Finance\MerchantTransaction\Event\TransactionCompleted;
use App\Domain\Finance\MerchantTransaction\Event\TransactionStarted;
use Ecotone\EventSourcing\Attribute\Projection;
use Ecotone\Modelling\Attribute\EventHandler;
use Ecotone\Modelling\Attribute\QueryHandler;
use Ramsey\Uuid\UuidInterface;

#[Projection("balance", ['balance-stream'])]
final class BalanceProjection
{
    private array $balances = [];
    private array $pendingTransactions = [];

    #[EventHandler(TransactionStarted::NAME)]
    public function applyTransactionStarted(TransactionStarted $event): void
    {
        $this->pendingTransactions[$event->transactionId->toString()] = [
            'userId' => $event->userId->toString(),
            'amount' => $event->amount
        ];
    }

    #[EventHandler(TransactionCancelled::NAME)]
    public function applyCancelTransaction(TransactionCancelled $event): void
    {
        unset($this->pendingTransactions[$event->transactionId->toString()]);
    }

    #[EventHandler(TransactionCompleted::NAME)]
    public function applyTransactionCompleted(TransactionCompleted $event): void
    {
        $transaction = $this->pendingTransactions[$event->transactionId->toString()];

        $this->balances[$transaction['userId']] -= $transaction['amount'];

        unset($this->pendingTransactions[$event->transactionId->toString()]);
    }

    #[EventHandler(DepositMade::NAME)]
    public function applyDepositMade(DepositMade $event): void
    {
        if (!isset($this->balances[$event->userId->toString()])) {
            $this->balances[$event->userId->toString()] = 0;
        }

        $this->balances[$event->userId->toString()] += $event->amount;
    }

    #[QueryHandler('getBalance')]
    public function getBalance(UuidInterface $userId): int
    {
        return $this->balances[$userId->toString()] ?? 0;
    }
}