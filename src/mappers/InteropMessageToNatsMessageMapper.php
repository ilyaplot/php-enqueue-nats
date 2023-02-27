<?php

declare(strict_types=1);

use ilyaplot\Enqueue\Nats\NatsMessage;
use Interop\Queue\Message;

final class InteropMessageToNatsMessageMapper
{
    public function map(Message $interopMessage): NatsMessage
    {
        $natsMessage = new NatsMessage(
            $interopMessage->getBody(),
            $interopMessage->getHeaders(),
            $interopMessage->getProperties()
        );
        $natsMessage->setRedelivered($interopMessage->isRedelivered());

        return $natsMessage;
    }
}