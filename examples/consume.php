<?php

declare(strict_types=1);

use Basis\Nats\Message\Payload;
use ilyaplot\Enqueue\Nats\NatsConnectionFactory;
use Interop\Queue\Message;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

require __DIR__ . '/../vendor/autoload.php';

$logger = new Logger("log");
//$logger->pushHandler(new ErrorLogHandler());

$factory = new NatsConnectionFactory(
    [
        'host' => '127.0.0.1',
        'port' => 4222,
    ],
    $logger
);

$context = $factory->createContext();

$queue = $context->createQueue('foo');
$consumer = $context->createConsumer($queue);
$subscriptionConsumer = $context->createSubscriptionConsumer();

$subscriptionConsumer->subscribe($consumer, function (Message $message) {
    var_dump($message);
});

while (true) {
    $subscriptionConsumer->consume();
}

echo 'Done' . "\n";