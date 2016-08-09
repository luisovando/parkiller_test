<?php

namespace ParkillerDemo\Listeners;

use ParkillerDemo\Events\UpdatePosition;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatePositionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UpdatePosition  $event
     * @return void
     */
    public function handle(UpdatePosition $event)
    {
        //
    }
}
