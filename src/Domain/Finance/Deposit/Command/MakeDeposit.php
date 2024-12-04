<?php

declare(strict_types=1);

namespace App\Domain\Finance\Deposit\Command;

use Ramsey\Uuid\UuidInterface;

final readonly class MakeDeposit
{
    public function __construct(
        public UuidInterface $depositId,
        public UuidInterface $userId,
        public int           $amount,
    ) {}
}