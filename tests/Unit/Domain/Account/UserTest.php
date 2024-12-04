<?php

declare(strict_types=1);

namespace Tests\App\Unit\Account\Domain;

use App\Domain\Account\User\Command\ChangeUserName;
use App\Domain\Account\User\Command\RegisterUser;
use App\Domain\Account\User\Event\UserNameChanged;
use App\Domain\Account\User\Event\UserRegistered;
use App\Domain\Account\User\User;
use App\Infrastructure\UuidConverter;
use Ecotone\Lite\EcotoneLite;
use Ecotone\Messaging\Config\ServiceConfiguration;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class UserTest extends TestCase
{
    #[Test]
    public function it_registers_new_user(): void
    {
        $ecotoneLite = EcotoneLite::bootstrapFlowTestingWithEventStore(
            containerOrAvailableServices: [new UuidConverter()],
            configuration: ServiceConfiguration::createWithDefaults()->withNamespaces(['App\Domain\Account\User', 'App\Infrastructure'])
        );

        $command = new RegisterUser(Uuid::uuid4(),'John Doe');

        $this->assertEquals(
            [
                new UserRegistered($command->userId, $command->name)
            ],
            $ecotoneLite
                ->sendCommand($command)
                ->getRecordedEvents()
        );
    }

    #[Test]
    public function it_changes_user_name(): void
    {
        $ecotoneLite = EcotoneLite::bootstrapFlowTestingWithEventStore(
            containerOrAvailableServices: [new UuidConverter()],
            configuration: ServiceConfiguration::createWithDefaults()->withNamespaces(['App\Domain\Account\User', 'App\Infrastructure'])
        );

        $userId = Uuid::uuid4();

        $this->assertEquals(
            [
                new UserNameChanged($userId, 'Jane Doe')
            ],
            $ecotoneLite
                ->sendCommand(new RegisterUser($userId, 'John Doe'))
                ->discardRecordedMessages()
                ->sendCommand(new ChangeUserName($userId, 'Jane Doe'))
                ->getRecordedEvents()
        );
    }
}