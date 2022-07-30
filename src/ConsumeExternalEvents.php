<?php

namespace Vmorozov\EventBackboneLaravel;

use Illuminate\Console\Command;

class ConsumeExternalEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'external_events:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume external events from event backbone';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(EventBackboneConsumer $consumer)
    {
        while (true) {
            $consumer->consume();
        }
    }
}
