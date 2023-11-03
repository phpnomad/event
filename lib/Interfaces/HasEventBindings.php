<?php

namespace PHPNomad\Events\Interfaces;

interface HasEventBindings
{
    public function getEventBindings(): array;
}