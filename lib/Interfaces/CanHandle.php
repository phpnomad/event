<?php

namespace PHPNomad\Events\Interfaces;

interface CanHandle
{
    /**
     * Does an action against an event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event): void;
}