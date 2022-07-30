<?php

namespace Vmorozov\EventBackboneLaravel\Consumer;

use ErrorException;
use Exception;
use Illuminate\Support\Facades\Log;
use RdKafka\KafkaConsumer;
use RdKafka\Message;

class KafkaEventBackboneConsumer extends AbstractEventBackboneConsumer
{
    private KafkaConsumer $consumer;

    public function __construct(KafkaConsumer $consumer, ConsumedExternalEventsMap $eventsMap)
    {
        parent::__construct($eventsMap);
        $this->consumer = $consumer;
        $consumer->subscribe(config('event-backbone-laravel.topics_to_subscribe'));
    }

    protected function getMessage(): Message
    {
        return $this->consumer->consume(60 * 1000);
    }

    protected function validateMessage($message): bool
    {
        switch ($message->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
                return true;
            case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                echo "No more messages; will wait for more\n";
                return false;
            case RD_KAFKA_RESP_ERR__TIMED_OUT:
                echo "Timed out\n";
                return false;
            default:
                // todo: add here specialized exception
                throw new Exception($message->errstr(), $message->err);
        }
    }

    protected function decodeMessage($message): ExternalConsumedEvent
    {
        // todo: move logging somewhere else or improve it
        echo json_encode($message->payload) . PHP_EOL;

        try {
            $decodedMessage = json_decode($message->payload, true);
            $data = json_decode($decodedMessage['body']['data'], true);
            $name = $decodedMessage['body']['name'];
        } catch (ErrorException $exception) {
            Log::debug('Error in Kafka consumer [serialized]: ' . serialize($message));
            Log::debug('Error in Kafka consumer [payload]: ' . $message->payload);
            Log::debug(PHP_EOL.PHP_EOL.PHP_EOL);
            throw new Exception($exception->getMessage());
        }

//        todo: add here some errors handling

        return $this->createEvent($name, $data);
    }

    protected function afterMessageProcessed($message): void
    {
        $this->consumer->commitAsync($message);
    }
}
