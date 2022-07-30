<?php

namespace Vmorozov\EventBackboneLaravel;

use Exception;
use RdKafka\KafkaConsumer;
use RdKafka\Message;

abstract class AbstractEventBackboneConsumer implements EventBackboneConsumer
{
    protected ConsumedExternalEventsMap $eventsMap;

    public function __construct(ConsumedExternalEventsMap $eventsMap)
    {
        $this->eventsMap = $eventsMap;
    }

    public function consume(): void
    {
        $message = $this->getMessage();
        if (!$this->validateMessage($message)) {
            return;
        }
        $this->processMessage($message);
        $this->afterMessageProcessed($message);
    }

    protected abstract function getMessage();
    protected abstract function validateMessage($message): bool;

    protected function processMessage($message): void
    {
        event($this->decodeMessage($message));
    }

    protected abstract function decodeMessage($message): ExternalConsumedEvent;

    protected function createEvent(string $eventName, array $eventData): ExternalConsumedEvent
    {
        $eventClass = $this->eventsMap->getClassForEventName($eventName);
        return new $eventClass($eventData);
    }

    protected function afterMessageProcessed($message): void
    {

    }
}
