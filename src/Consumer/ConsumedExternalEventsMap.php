<?php

namespace Vmorozov\EventBackboneLaravel\Consumer;

class ConsumedExternalEventsMap
{
    private array $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function getClassForEventName(string $eventName): string
    {
        return $this->map[$eventName];
    }

//    public function getEventNameForEvent(string $eventName): string
//    {
//        return $this->map[$eventName];
//    }
}
