<?php

namespace ParkillerDemo\Entities;

class Client extends Entity
{

    protected $appends = [ 'type', 'full_name' ];


    public function getTypeAttribute()
    {
        return "client";
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
