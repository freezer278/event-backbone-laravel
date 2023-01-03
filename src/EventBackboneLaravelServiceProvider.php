<?php

namespace Vmorozov\EventBackboneLaravel;

use Illuminate\Support\Facades\Event;
use RdKafka\Conf;
use RdKafka\Producer;
use RdKafka\TopicConf;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vmorozov\EventBackboneLaravel\Commands\ConsumeExternalEventsCommand;
use Vmorozov\EventBackboneLaravel\Consumer\ConsumedExternalEventsMap;
use Vmorozov\EventBackboneLaravel\Consumer\Context\ConsumedEventContextApplier;
use Vmorozov\EventBackboneLaravel\Consumer\Context\VoidApplier;
use Vmorozov\EventBackboneLaravel\Consumer\EventBackboneConsumer;
use Vmorozov\EventBackboneLaravel\Consumer\KafkaEventBackboneConsumer;
use Vmorozov\EventBackboneLaravel\Producer\Context\DefaultProvider;
use Vmorozov\EventBackboneLaravel\Producer\Context\ProducedEventContextProvider;
use Vmorozov\EventBackboneLaravel\Producer\EventBackboneProducer;
use Vmorozov\EventBackboneLaravel\Producer\ExternalEventsProducingSubscriber;
use Vmorozov\EventBackboneLaravel\Producer\KafkaEventBackboneProducer;

class EventBackboneLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('event-backbone-laravel')
            ->hasConfigFile()
            ->hasCommand(ConsumeExternalEventsCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->setupContext();

        $this->app->instance(
            ConsumedExternalEventsMap::class,
            new ConsumedExternalEventsMap(config('event-backbone-laravel.consumed_event_names_map'))
        );

        $this->connectEventBackboneDriver();

        Event::subscribe(ExternalEventsProducingSubscriber::class);
    }

    protected function connectEventBackboneDriver(): void
    {
        // todo: add here checks for driver name to set up required driver
        $this->setupKafka();
        $this->app->bind(EventBackboneProducer::class, KafkaEventBackboneProducer::class);
        $this->app->bind(EventBackboneConsumer::class, KafkaEventBackboneConsumer::class);
    }

    private function setupKafka(): void
    {
        $conf = new Conf();
        $conf->set('metadata.broker.list', config('event-backbone-laravel.drivers.kafka.brokers'));
        $conf->set('compression.type', 'snappy');
        $conf->set('group.id', config('event-backbone-laravel.drivers.kafka.consumer_group_id'));
        if (config('event-backbone-laravel.drivers.kafka.debug')) {
            $conf->set('log_level', LOG_DEBUG);
            $conf->set('debug', 'all');
        }
        $topicConf = new TopicConf();
        $topicConf->set('auto.offset.reset', 'smallest');
        $conf->setDefaultTopicConf($topicConf);

        $this->app->instance(Conf::class, $conf);

        $this->app->bind(Producer::class, function () use ($conf) {
            return new Producer($conf);
        });
    }

    private function setupContext(): void
    {
        $this->app->bind(
            ProducedEventContextProvider::class,
            config('event-backbone-laravel.context.provider_class', DefaultProvider::class)
        );

        $this->app->bind(
            ConsumedEventContextApplier::class,
            config('event-backbone-laravel.context.applier_class', VoidApplier::class)
        );
    }
}
