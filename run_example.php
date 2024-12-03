<?php

use App\Domain\Deposit\Command\MakeDeposit;
use App\Domain\MerchantTransaction\Command\CompleteTransaction;
use App\Domain\MerchantTransaction\Command\StartTransaction;
use App\Domain\User\Command\ChangeUserName;
use App\Domain\User\Command\RegisterUser;
use Ecotone\Dbal\DbalConnection;
use Ecotone\Lite\EcotoneLiteApplication;
use Enqueue\Dbal\DbalConnectionFactory;
use PHPUnit\Framework\Assert;
use Ramsey\Uuid\Uuid;

require __DIR__ . "/vendor/autoload.php";
$messagingSystem = EcotoneLiteApplication::bootstrap(
    [
        DbalConnectionFactory::class => DbalConnection::fromDsn('pgsql://ecotone:secret@database:5432/ecotone'),
    ],
    pathToRootCatalog: __DIR__
);
$commandBus = $messagingSystem->getCommandBus();

$userOne = Uuid::uuid4();
$commandBus->send(new RegisterUser($userOne, "Michal Kowalski"));
$commandBus->send(new ChangeUserName($userOne, "Michal Nowak"));

$userTwo = Uuid::uuid4();
$commandBus->send(new RegisterUser($userTwo, "John Doe"));

Assert::assertEquals(
    [
        $userOne->toString() => "Michal Nowak",
        $userTwo->toString() => "John Doe"
    ],
    $messagingSystem->getQueryBus()->sendWithRouting("getUsers")
);
echo "Users were registered successfully\n";

$transactionId = Uuid::uuid4();
$commandBus->send(new MakeDeposit(Uuid::uuid4(), $userOne, 600));
$commandBus->send(new StartTransaction($transactionId, $userOne, 250));
$commandBus->send(new CompleteTransaction($transactionId));

Assert::assertEquals(
    350,
    $messagingSystem->getQueryBus()->sendWithRouting("getBalance", $userOne)
);
Assert::assertEquals(
    0,
    $messagingSystem->getQueryBus()->sendWithRouting("getBalance", $userTwo)
);
echo "Balance was calculated successfully\n";
