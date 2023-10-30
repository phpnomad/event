<?php

namespace PHPNomad\Events\Interfaces;

interface CanListen
{
    /**
     * Sets up the required actions to listen to the thing.
     *
     * @return void
     */
    public function listen(): void;
}