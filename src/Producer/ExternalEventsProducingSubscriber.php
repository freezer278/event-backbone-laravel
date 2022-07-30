<?php

namespace Vmorozov\EventBackboneLaravel\Producer;

use Illuminate\Events\Dispatcher;

class ExternalEventsProducingSubscriber
{
    private EventBackboneProducer $eventBackboneProducer;

    public function __construct(EventBackboneProducer $eventBackbonePublisher)
    {
        $this->eventBackboneProducer = $eventBackbonePublisher;
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
        $this->eventBackboneProducer->produce($event);
    }
}
