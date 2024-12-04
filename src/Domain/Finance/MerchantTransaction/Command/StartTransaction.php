<?php

declare(strict_types=1);

namespace App\Domain\Finance\MerchantTransaction\Command;

use Ramsey\Uuid\UuidInterface;

final readonly class StartTransaction
{
    public function __construct(
        public UuidInterface $transactionId,
        public UuidInterface $userId,
        public int $amount,
    ) {}
}