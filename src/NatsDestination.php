<?php

declare(strict_types=1);

namespace ilyaplot\Enqueue\Nats;

use Interop\Queue\Queue;
use Interop\Queue\Topic;

class NatsDestination implements Queue, Topic
{
    public function __construct(private readonly string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQueueName(): string
    {
        return $this->getName();
    }

    public function getTopicName(): string
    {
        return $this->getName();
    }
}