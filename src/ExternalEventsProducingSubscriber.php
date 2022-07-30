<?php

namespace Vmorozov\EventBackboneLaravel;

use Illuminate\Events\Dispatcher;

class ExternalEventsProducingSubscriber
{
    private EventBackboneProducer $eventBackbonePublisher;

    public function __construct(EventBackboneProducer $eventBackbonePublisher)
    {
        $this->eventBackbonePublisher = $eventBackbonePublisher;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            ExternalEvent::class,
            [ExternalEventsProducingSubscriber::class, 'publishExternalEvent']
        );
    }

    public function publishExternalEvent(ExternalEvent $event): void
    {
        $this->eventBackbonePublisher->produce($event);
    }
}
