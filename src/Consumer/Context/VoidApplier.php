<?php

namespace Vmorozov\EventBackboneLaravel\Consumer\Context;

class VoidApplier implements ConsumedEventContextApplier
{
    public function apply(array $context): void
    {
    }
}
