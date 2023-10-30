<?php

namespace PHPNomad\Events\Interfaces;

interface CanMutate
{
    /**
     * Mutates data inside the event, and returns the mutated event
     * 
     * @template TEvent of Event
     * @param TEvent $event
     * @return TEvent
     */
    public function mutate(Event $event): Event;
}