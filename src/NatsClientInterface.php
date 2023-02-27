<?php

declare(strict_types=1);

namespace ilyaplot\Enqueue\Nats;

use Basis\Nats\Api;
use Basis\Nats\Configuration;
use Closure;
use Psr\Log\LoggerInterface;
use Throwable;

interface NatsClientInterface
{
    public function api($command, array $args = [], ?Closure $callback = null): ?object;

    /**
     * @return $this
     * @throws Throwable
     */
    public function connect(): self;

    public function dispatch(string $name, mixed $payload, ?float $timeout = null);

    public function getApi(): Api;

    public function ping(): bool;

    public function publish(string $name, mixed $payload, ?string $replyTo = null): self;

    public function request(string $name, mixed $payload, Closure $handler): self;

    public function subscribe(string $name, Closure $handler): self;

    public function subscribeQueue(string $name, string $group, Closure $handler);

    public function unsubscribe(string $name): self;

    public function setDelay(float $delay, string $mode = Configuration::DELAY_CONSTANT): self;

    public function setLogger(?LoggerInterface $logger): self;

    public function setTimeout(float $value): self;

    /**
     * @throws Throwable
     */
    public function process(null | int | float $timeout = 0);

    public function setName(string $name): self;

    public function skipInvalidMessages(bool $skipInvalidMessages): self;
}
