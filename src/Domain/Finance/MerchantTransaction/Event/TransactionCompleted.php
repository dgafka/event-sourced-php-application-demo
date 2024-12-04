<?php

declare(strict_types=1);

namespace App\Domain\Finance\MerchantTransaction\Event;

use Ecotone\Modelling\Attribute\NamedEvent;
use Ramsey\Uuid\UuidInterface;

#[NamedEvent(self::NAME)]
final readonly class TransactionCompleted
{
    public const string NAME = 'transaction.completed';

    public function __construct(
        public UuidInterface $transactionId,
    ) {}
}