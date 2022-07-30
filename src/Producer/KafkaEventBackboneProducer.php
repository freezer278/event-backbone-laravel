<?php

namespace Vmorozov\EventBackboneLaravel\Producer;

use Exception;
use RdKafka\Producer;

class KafkaEventBackboneProducer implements EventBackboneProducer
{
    private Producer $producer;

    public function __construct(Producer $producer)
    {
        $this->producer = $producer;
    }

    public function produce(ExternalEvent $event): void
    {
        $topic = $this->producer->newTopic($event->getTopic());

        // RD_KAFKA_PARTITION_UA, lets librdkafka choose the partition.
        // Messages with the same "$key" will be in the same topic partition.
        // This ensure that messages are consumed in order.
        $topic->produce(
            RD_KAFKA_PARTITION_UA,
            0,
            $this->buildPayload($event),
            $event->getKey()
        );

        // pull for any events
        $this->producer->poll(0);

        $this->flush();
    }


    /**
     * librdkafka flush waits for all outstanding producer requests to be handled.
     * It ensures messages produced properly.
     *
     * @param int $timeout "timeout in milliseconds"
     * @return void
     */
    protected function flush(int $timeout = 10000)
    {
        $result = $this->producer->flush($timeout);

        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            // todo: add more specialized exception
            throw new Exception('librdkafka unable to perform flush, messages might be lost');
        }
    }

    /**
     * Build kafka message payload in json encoded format
     *
     * @param ExternalEvent $event
     * @return string
     */
    protected function buildPayload(ExternalEvent $event): string
    {
        return json_encode([
            'body' => [
                'name' => $event->getName(),
                'data' => $event->getPayload(),
            ],
            'headers' => $event->getHeaders(),
        ]);
    }
}
