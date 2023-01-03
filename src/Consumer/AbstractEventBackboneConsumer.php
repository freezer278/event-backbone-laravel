<?php

namespace Vmorozov\EventBackboneLaravel\Consumer;

use Vmorozov\EventBackboneLaravel\Consumer\Context\ConsumedEventContextApplier;

abstract class AbstractEventBackboneConsumer implements EventBackboneConsumer
{
    protected ConsumedExternalEventsMap $eventsMap;
    private ConsumedEventContextApplier $contextApplier;

    public function __construct(ConsumedExternalEventsMap $eventsMap, ConsumedEventContextApplier $contextApplier)
    {
        $this->eventsMap = $eventsMap;
        $this->contextApplier = $contextApplier;
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

    protected function applyContext(array $context): void
    {
        $this->contextApplier->apply($context);
    }
}
