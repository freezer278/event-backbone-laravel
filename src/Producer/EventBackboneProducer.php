<?php

namespace Vmorozov\EventBackboneLaravel\Producer;

interface EventBackboneProducer
{
    public function produce(ExternalEvent $event): void;
}
