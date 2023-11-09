<?php

namespace PHPNomad\Events\Interfaces;

interface CanListen
{
    /**
     * Listen for events.
     *
     * @return void
     */
    public function listen(): void;
}