<?php

declare(strict_types=1);

namespace ilyaplot\Enqueue\Nats\Client;

use Basis\Nats\Client;
use Basis\Nats\Configuration;
use Closure;
use ilyaplot\Enqueue\Nats\NatsClientInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class Nats extends Client implements NatsClientInterface
{
    /**
     * @param string $name
     * @param mixed $payload
     * @param Closure $handler
     * @return $this
     */
    public function request(string $name, mixed $payload, Closure $handler): self
    {
        return parent::request($name, $payload, $handler);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        return parent::setName($name);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function unsubscribe(string $name): self
    {
        return parent::unsubscribe($name);
    }

    /**
     * @param string $name
     * @param Closure $handler
     * @return $this
     */
    public function subscribe(string $name, Closure $handler): self
    {
        return parent::subscribe($name, $handler);
    }

    /**
     * @return $this
     * @throws Throwable
     */
    public function connect(): self
    {
        return parent::connect();
    }

    /**
     * @param float $value
     * @return $this
     */
    public function setTimeout(float $value): self
    {
        return parent::setTimeout($value);
    }

    /**
     * @param float $delay
     * @param string $mode
     * @return $this
     */
    public function setDelay(float $delay, string $mode = Configuration::DELAY_CONSTANT): self
    {
        return parent::setDelay($delay, $mode);
    }

    /**
     * @param string $name
     * @param mixed $payload
     * @param string|null $replyTo
     * @return $this
     */
    public function publish(string $name, mixed $payload, ?string $replyTo = null): self
    {
        return parent::publish($name, $payload, $replyTo);
    }

    /**
     * @param bool $skipInvalidMessages
     * @return $this
     */
    public function skipInvalidMessages(bool $skipInvalidMessages): self
    {
        return parent::skipInvalidMessages($skipInvalidMessages);
    }

    /**
     * @param LoggerInterface|null $logger
     * @return $this
     */
    public function setLogger(?LoggerInterface $logger): self
    {
        return parent::setLogger($logger);
    }
}