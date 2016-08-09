<?php

namespace ParkillerDemo\Entities;

class Driver extends Entity
{

    protected $appends = [ 'type', 'full_name' ];


    public function getTypeAttribute()
    {
        return "driver";
    }


    public function getFullNameAttribute()
    {
        return $this->attributes['name'] . ' ' . $this->attributes['last_name'];
    }


    public function travels()
    {
        return $this->hasMany(Travel::getClass());
    }
}
