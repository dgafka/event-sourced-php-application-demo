<?php

declare(strict_types=1);

namespace App\Domain\MerchantTransaction\Event;

use Ecotone\Modelling\Attribute\NamedEvent;
use Ramsey\Uuid\UuidInterface;

#[NamedEvent(self::NAME)]
final readonly class TransactionStarted
{
    public const NAME = 'transaction.started';

    public function __construct(
        public UuidInterface $transactionId,
        public UuidInterface $userId,
        public int $amount,
    ) {}
}