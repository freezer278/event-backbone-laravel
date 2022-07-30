<?php

namespace Vmorozov\EventBackboneLaravel\Consumer;

interface EventBackboneConsumer
{
    public function consume(): void;
}
