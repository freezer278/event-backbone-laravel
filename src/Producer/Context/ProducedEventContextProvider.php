<?php

namespace Vmorozov\EventBackboneLaravel\Producer\Context;

interface ProducedEventContextProvider
{
    public function getContext(): array;
}
