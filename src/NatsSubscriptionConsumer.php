<?php

declare(strict_types=1);

namespace ilyaplot\Enqueue\Nats;

use Basis\Nats\Message\Payload;
use Exception;
use Interop\Queue\Consumer;
use Interop\Queue\Message;
use Interop\Queue\SubscriptionConsumer;
use JsonException;

class NatsSubscriptionConsumer implements SubscriptionConsumer
{
    public function __construct(private readonly NatsContext $context)
    {
    }

    public function consume(int $timeout = 0): void
    {
        $this->context->getNats()->process($timeout);
    }

    public function subscribe(Consumer $consumer, callable $callback): void
    {
        $this->context->getNats()->subscribe($consumer->getQueue()->getName(), function (Payload $payload) use (
            $callback
        ) {
            $callback($this->mapPayload($payload));
        });
    }

    public function unsubscribe(Consumer $consumer): void
    {
        $this->context->getNats()->unsubscribe($consumer->getQueue()->getName());
    }

    public function unsubscribeAll(): void
    {
        throw new Exception('Not implemented');
    }

    /**
     * @throws JsonException
     */
    private function mapPayload(Payload $payload): Message
    {
        $payloadBody = json_decode(json: $payload->body, associative: true, flags: JSON_THROW_ON_ERROR);

        return new NatsMessage(
            $payloadBody['body'] ?? '',
            $payloadBody['properties'] ?? [],
            $payloadBody->headers ?? [],
        );
    }
}