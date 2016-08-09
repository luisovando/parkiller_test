<?php
/**
 * Created by PhpStorm.
 * User: Desarrollo
 * Date: 08/08/2016
 * Time: 10:00 AM
 */

namespace ParkillerDemo\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use ParkillerDemo\Entities\Tracking;
use ParkillerDemo\Entities\Travel;

class TravelRepository extends EloquentRepository
{

    /**
     * @return Model
     */
    public function getModel()
    {
        return new Travel();
    }


    public function create($request)
    {
        $origin                        = array_get($request, 'origin');
        $destination                   = array_get($request, 'destination');
        $travel                        = new Travel();
        $travel->driver_id             = $origin['driver_id'];
        $travel->latitude_origin       = array_get($origin['position'], 'lat');
        $travel->longitude_origin      = array_get($origin['position'], 'lng');
        $travel->client_id             = $destination['client_id'];
        $travel->latitude_destination  = array_get($destination['position'], 'lat');
        $travel->longitude_destination = array_get($destination['position'], 'lng');;

        $travel->saveOrFail();

        $tracking = new Tracking();
        $tracking->forceFill([
            "latitude"  => $travel->latitude_origin,
            "longitude" => $travel->longitude_origin
        ]);

        $travel->trackings()->save($tracking);

        return $travel->with([ 'driver', 'client' ])->get();
    }
}