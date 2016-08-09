<?php

namespace ParkillerDemo\Entities;

class Travel extends Entity
{

    public function client()
    {
        return $this->belongsTo(Client::getClass());
    }


    public function driver()
    {
        return $this->belongsTo(Driver::getClass());
    }


    public function trackings()
    {
        return $this->hasMany(Tracking::getClass());
    }
}
