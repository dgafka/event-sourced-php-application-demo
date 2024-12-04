<?php

declare(strict_types=1);

namespace App\Projection\Account;

use App\Domain\Account\User\Event\UserNameChanged;
use App\Domain\Account\User\Event\UserRegistered;
use Ecotone\EventSourcing\Attribute\Projection;
use Ecotone\Modelling\Attribute\EventHandler;
use Ecotone\Modelling\Attribute\QueryHandler;

#[Projection('user-list', ['user-stream'])]
final class UserProjection
{
    private array $users = [];

    #[EventHandler(UserRegistered::NAME)]
    public function onUserRegistered(array $event): void
    {
        $this->users[$event['userId']] = $event['name'];
    }

    #[EventHandler(UserNameChanged::NAME)]
    public function onUserNameChanged(array $event): void
    {
        $this->users[$event['userId']] = $event['name'];
    }

    #[QueryHandler('getUsers')]
    public function getUsers(): array
    {
        return $this->users;
    }
}