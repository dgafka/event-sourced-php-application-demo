<?php

declare(strict_types=1);

namespace App\Domain\MerchantTransaction\Event;

use Ecotone\Modelling\Attribute\NamedEvent;
use Ramsey\Uuid\UuidInterface;

#[NamedEvent(self::NAME)]
final readonly class TransactionCancelled
{
    public const string NAME = "transaction.cancelled";

    public function __construct(
        public UuidInterface $transactionId,
    ) {}
}