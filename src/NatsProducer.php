<?php

declare(strict_types=1);

namespace ilyaplot\Enqueue\Nats;

use Basis\Nats\Message\Payload;
use Interop\Queue\Destination;
use Interop\Queue\Exception\InvalidDestinationException;
use Interop\Queue\Exception\InvalidMessageException;
use Interop\Queue\Exception\PriorityNotSupportedException;
use Interop\Queue\Message;
use Interop\Queue\Producer;
use Ramsey\Uuid\Uuid;

class NatsProducer implements Producer
{
    private ?int $deliveryDelay;
    private ?int $timeToLive;

    public function __construct(
        private readonly NatsContext $context
    ) {
    }

    /**
     * @param NatsDestination $destination
     * @param NatsMessage $message
     * @throws InvalidDestinationException
     * @throws InvalidMessageException
     */
    public function send(Destination $destination, Message $message): void
    {
        InvalidDestinationException::assertDestinationInstanceOf($destination, NatsDestination::class);
        InvalidMessageException::assertMessageInstanceOf($message, NatsMessage::class);

        $message->setMessageId(Uuid::uuid4()->toString());
        $message->setHeader('attempts', 0);

        $payload = new Payload(
            json_encode([
                'body' => $message->getBody(),
                'properties' => $message->getProperties(),
            ]),
            $message->getHeaders(),
            $destination->getName(),
        );


        $this->context->getNats()->publish(
            $destination->getName(),
            $payload,
            $message->getReplyTo(),
        );
    }

    /**
     * @return self
     */
    public function setDeliveryDelay(int $deliveryDelay = null): Producer
    {
        $this->deliveryDelay = $deliveryDelay;

        return $this;
    }

    public function getDeliveryDelay(): ?int
    {
        return $this->deliveryDelay;
    }

    /**
     * @return NatsProducer
     * @throws PriorityNotSupportedException
     */
    public function setPriority(int $priority = null): Producer
    {
        if (null === $priority) {
            return $this;
        }

        // @todo Implement setPriority() method.
        throw PriorityNotSupportedException::providerDoestNotSupportIt();
    }

    public function getPriority(): ?int
    {
        // @todo Implement getPriority() method.
        return null;
    }

    /**
     * @return self
     */
    public function setTimeToLive(int $timeToLive = null): Producer
    {
        // @todo Implement setTimeToLive() method.
        $this->timeToLive = $timeToLive;

        return $this;
    }

    public function getTimeToLive(): ?int
    {
        // @todo Implement getTimeToLive() method.
        return $this->timeToLive;
    }
}