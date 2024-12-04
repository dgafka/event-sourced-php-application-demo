<?php

declare(strict_types=1);

namespace App\Domain\Finance\Deposit\Event;

use Ecotone\Modelling\Attribute\NamedEvent;
use Ramsey\Uuid\UuidInterface;

#[NamedEvent(self::NAME)]
final readonly class DepositMade
{
    public const string NAME = 'deposit.made';

    public function __construct(
        public UuidInterface $depositId,
        public UuidInterface $userId,
        public int           $amount,
    ) {}
}