<?php

namespace Vmorozov\EventBackboneLaravel\Producer\Context;

class DefaultProvider implements ProducedEventContextProvider
{
    public function getContext(): array
    {
        return [];
    }
}
