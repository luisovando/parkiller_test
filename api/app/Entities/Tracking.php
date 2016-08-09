<?php

namespace ParkillerDemo\Entities;

class Tracking extends Entity
{

    public function travel()
    {
        return $this->belongsTo(Travel::getClass());
    }
}
