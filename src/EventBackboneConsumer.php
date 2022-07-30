<?php

namespace Vmorozov\EventBackboneLaravel;

interface EventBackboneConsumer
{
    public function consume(): void;
}
