<?php

namespace Vmorozov\EventBackboneLaravel\Consumer\Context;

interface ConsumedEventContextApplier
{
    public function apply(array $context): void;
}
