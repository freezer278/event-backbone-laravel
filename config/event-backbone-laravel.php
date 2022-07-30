<?php

// config for Vmorozov/EventBackboneLaravel
return [
    /**
     * Select event backbone driver.
     * Supported drivers: kafka.
     */
    'driver' => 'kafka',
    /**
     * Here should be topics for subscription.
     * Example:
     * 'users', 'posts'
     */
    'topics_to_subscribe' => [

    ],
    /**
     * Map consumed events from event backbone to Event classes in your project. Name => EventClass.
     * Example:
     *  'test_event' => TestEvent::class,
     */
    'consumed_event_names_map' => [

    ],
    /**
     * Driver-specific setups.
     */
    'drivers' => [
        'kafka' => [
            'brokers' => env('KAFKA_BROKERS', '127.0.0.1'),
            'consumer_group_id' => env('KAFKA_CONSUMER_GROUP_ID', env('APP_NAME', 'laravel')),
            'debug' => env('KAFKA_DEBUG', false),
        ],
    ],
];
