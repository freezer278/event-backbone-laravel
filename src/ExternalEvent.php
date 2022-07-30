<?php

namespace Vmorozov\EventBackboneLaravel;

interface ExternalEvent
{
    public function getTopic(): string;

    public function getName(): string;

    public function getKey(): string;

    public function getPayload();

    public function getHeaders(): array;
}
