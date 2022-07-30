<?php

namespace Vmorozov\EventBackboneLaravel\Consumer;

interface ExternalConsumedEvent
{
    public function __construct(array $payload);
}
