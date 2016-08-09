<?php

namespace ParkillerDemo\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use ParkillerDemo\Entities\Entity;

class UpdatePosition extends Event implements ShouldBroadcast
{

    use SerializesModels;

    /**
     * @var Entity
     */
    public $entity;


    /**
     * Create a new event instance.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }


    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [ 'parkiller_demo_channel' ];
    }

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'parkiller-update-status';
    }
}
