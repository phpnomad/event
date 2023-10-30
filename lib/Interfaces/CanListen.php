<?php

namespace PHPNomad\Events\Interfaces;

interface CanListen
{
    /**
     * Sets up the required actions to listen for an event.
     *
     * @return void
     */
    public function listen(): void;
}