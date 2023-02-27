<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use ilyaplot\Enqueue\Nats\NatsConnectionFactory;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

$logger = new Logger("log");
$logger->pushHandler(new ErrorLogHandler());

$factory = new NatsConnectionFactory(
    [
        'host' => '127.0.0.1',
        'port' => 4222,
    ],
    $logger
);


$context = $factory->createContext();

$fooQueue = $context->createQueue('foo');
$message = $context->createMessage('Hello Bar!');
$message->setHeader('Test', 'test');
$message->setProperty('aaa', 'bbbb');

while (true) {
    $context->createProducer()->send($fooQueue, $message);
}

echo 'Done' . "\n";