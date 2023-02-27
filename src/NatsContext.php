<?php

declare(strict_types=1);

namespace ilyaplot\Enqueue\Nats;

use Closure;
use Interop\Queue\Consumer;
use Interop\Queue\Context;
use Interop\Queue\Destination;
use Interop\Queue\Exception\TemporaryQueueNotSupportedException;
use Interop\Queue\Message;
use Interop\Queue\Producer;
use Interop\Queue\Queue;
use Interop\Queue\SubscriptionConsumer;
use Interop\Queue\Topic;
use InvalidArgumentException;
use LogicException;

class NatsContext implements Context
{
    private ?NatsClientInterface $nats = null;

    private ?Closure $natsFactory = null;

    /**
     * Callable must return instance of Nats once called.
     *
     * @param NatsClientInterface|callable $nats
     */
    public function __construct(mixed $nats)
    {
        if ($nats instanceof NatsClientInterface) {
            $this->nats = $nats;
        } elseif (is_callable($nats)) {
            $this->natsFactory = $nats;
        } else {
            throw new InvalidArgumentException(sprintf(
                'The $nats argument must be either %s or callable that returns %s once called.',
                NatsClientInterface::class,
                NatsClientInterface::class
            ));
        }
    }

    /**
     * @return NatsMessage
     */
    public function createMessage(string $body = '', array $properties = [], array $headers = []): Message
    {
        return new NatsMessage($body, $properties, $headers);
    }

    /**
     * @return NatsDestination
     */
    public function createTopic(string $topicName): Topic
    {
        return new NatsDestination($topicName);
    }

    /**
     * @return NatsDestination
     */
    public function createQueue(string $queueName): Queue
    {
        return new NatsDestination($queueName);
    }

    public function createTemporaryQueue(): Queue
    {
        throw TemporaryQueueNotSupportedException::providerDoestNotSupportIt();
    }

    /**
     * @return NatsProducer
     */
    public function createProducer(): Producer
    {
        return new NatsProducer($this);
    }

    public function createConsumer(Destination $destination): Consumer
    {
        return new NatsConsumer($this, $destination);
    }

    /**
     * @return NatsSubscriptionConsumer
     */
    public function createSubscriptionConsumer(): SubscriptionConsumer
    {
        return new NatsSubscriptionConsumer($this);
    }

    /**
     * @param NatsDestination $queue
     */
    public function purgeQueue(Queue $queue): void
    {
        throw new LogicException('Not implemented');
    }

    public function close(): void
    {
        // Method not implemented
    }

    public function getNats(): NatsClientInterface
    {
        return $this->nats ?? $this->getNatsThroughFactory();
    }

    private function getNatsThroughFactory(): NatsClientInterface
    {
        $nats = call_user_func($this->natsFactory);
        if (!$nats instanceof NatsClientInterface) {
            throw new LogicException(sprintf(
                'The factory must return instance of %s. It returned %s',
                NatsClientInterface::class,
                is_object($nats) ? get_class($nats) : gettype($nats)
            ));
        }

        $this->nats = $nats;

        return $this->nats;
    }
}
