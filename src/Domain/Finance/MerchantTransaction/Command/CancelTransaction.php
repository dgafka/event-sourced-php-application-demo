<?php

declare(strict_types=1);

namespace App\Domain\Finance\MerchantTransaction\Command;

use Ramsey\Uuid\UuidInterface;

final readonly class CancelTransaction
{
    public function __construct(
        public UuidInterface $transactionId,
    ) {}
}