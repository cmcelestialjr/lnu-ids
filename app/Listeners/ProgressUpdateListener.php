<?php

namespace App\Listeners;

use App\Events\ProgressUpdateEvent;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProgressUpdateListener
{
    /**
     * Create the event listener.
     */
    // public function __construct($progress)
    // {
    //     //
    // }

    /**
     * Handle the event.
     */
    public function handle(ProgressUpdateEvent $event): void
    {
        $progress = $event->progress;

        // Broadcast the progress update to the client
        //Broadcast::channel('progress')->event(new ProgressUpdateEvent($progress));
        broadcast(new ProgressUpdateEvent($progress))->toOthers();
    }
}
