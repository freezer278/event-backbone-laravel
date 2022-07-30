<?php

namespace Vmorozov\EventBackboneLaravel;

interface EventBackboneProducer
{
    public function produce(ExternalEvent $event): void;
}
