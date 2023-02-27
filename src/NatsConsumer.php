<?php

declare(strict_types=1);

namespace ilyaplot\Enqueue\Nats;

use Interop\Queue\Consumer;
use Interop\Queue\Message;
use Interop\Queue\Queue;
use LogicException;

class NatsConsumer implements Consumer
{
    public function __construct(
        private readonly NatsContext $context,
        private readonly NatsDestination $destination
    ) {
    }

    public function getQueue(): Queue
    {
        return $this->destination;
    }

    public function receive(int $timeout = 0): ?Message
    {
        throw new LogicException('Not implemented');
    }

    public function receiveNoWait(): ?Message
    {
        throw new LogicException('Not implemented');
    }

    public function acknowledge(Message $message): void
    {
        throw new LogicException('Not implemented');
    }

    public function reject(Message $message, bool $requeue = false): void
    {
        throw new LogicException('Not implemented');
    }
}