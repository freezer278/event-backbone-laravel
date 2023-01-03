<?php

namespace Vmorozov\EventBackboneLaravel\Producer;

interface ExternalEvent
{
    public function getTopic(): string;

    public function getName(): string;

    public function getKey(): string;

    public function getPayload(): array;

    public function getHeaders(): array;
}
