<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\Command\ChangeUserName;
use App\Domain\User\Command\RegisterUser;
use App\Domain\User\Event\UserNameChanged;
use App\Domain\User\Event\UserRegistered;
use Ecotone\EventSourcing\Attribute\AggregateType;
use Ecotone\EventSourcing\Attribute\Stream;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\EventSourcingAggregate;
use Ecotone\Modelling\Attribute\EventSourcingHandler;
use Ecotone\Modelling\Attribute\Identifier;
use Ecotone\Modelling\WithAggregateVersioning;
use Ramsey\Uuid\UuidInterface;

#[Stream('user-stream')]
#[AggregateType('user-aggregate')]
#[EventSourcingAggregate]
final class User
{
    use WithAggregateVersioning;

    #[Identifier]
    private UuidInterface $userId;

    #[CommandHandler]
    public static function register(RegisterUser $command): array
    {
        return [
            new UserRegistered($command->userId, $command->name)
        ];
    }

    #[CommandHandler]
    public function changeName(ChangeUserName $command): array
    {
        return [
            new UserNameChanged($this->userId, $command->name)
        ];
    }

    #[EventSourcingHandler]
    public function applyUserRegistered(UserRegistered $event): void
    {
        $this->userId = $event->userId;
    }
}