<?php

declare(strict_types=1);

namespace ilyaplot\Enqueue\Nats;

use Basis\Nats\Configuration;
use Closure;
use ilyaplot\Enqueue\Nats\Client\Nats;
use Interop\Queue\ConnectionFactory;
use Interop\Queue\Context;
use InvalidArgumentException;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class NatsConnectionFactory implements ConnectionFactory
{
    private Nats $nats;
    private readonly ?int $delay;
    private readonly ?string $delayMode;

    /**
     * @see https://github.com/basis-company/nats.php#connection
     *
     * @param Nats | Closure | array{
     *     host?: ?string,
     *     jwt?: ?string,
     *     lang?: ?string,
     *     pass?: ?string,
     *     pedantic?: ?bool,
     *     port?: ?int,
     *     reconnect?: ?bool,
     *     timeout?: ?int,
     *     token?: ?string,
     *     user?: ?string,
     *     nkey?: ?string,
     *     verbose?: ?bool,
     *     version?: ?string,
     *     pingInterval?: ?int,
     *     inboxPrefix?: ?string,
     *     tlsKeyFile?: ?string,
     *     tlsCertFile?: ?string,
     *     tlsCaFile?: ?string,
     *     delay?: ?int,
     *     delayMode?: ?string,
     * } $nats
     * @param LoggerInterface|null $logger
     */
    public function __construct(array | Nats | Closure $nats = [], ?LoggerInterface $logger = null)
    {
        if ($nats instanceof Nats) {
            $this->nats = $nats;
        } elseif ($nats instanceof Closure) {
            $this->nats = $nats();
        } elseif (is_array($nats)) {
            $natsConfig = $nats;
            $this->delay = $config['delay'] ?? null;
            $this->delayMode = $config['delayMode'] ?? null;
            unset($natsConfig['delay'], $natsConfig['delayMode']);
            $natsConfiguration = new Configuration($nats);
            $this->nats = new Nats($natsConfiguration);
            if ($this->delay !== null) {
                $this->nats->setDelay($this->delay, $this->delayMode ?? Configuration::DELAY_CONSTANT);
            }

            if ($logger !== null) {
                $this->nats->setLogger($logger);
            }

            return $this->nats;
        } else {
            throw new InvalidArgumentException(
                'The $nats argument must be either array, Nats or callable that returns Nats once called.'
            );
        }

        return $this->nats;
    }

    public function createContext(): Context
    {
        return new NatsContext($this->nats);
    }
}
