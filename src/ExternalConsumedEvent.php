<?php

namespace Vmorozov\EventBackboneLaravel;

interface ExternalConsumedEvent
{
    public function __construct(array $payload);
}
