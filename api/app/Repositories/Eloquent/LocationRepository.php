<?php
/**
 * Created by PhpStorm.
 * User: Desarrollo
 * Date: 08/08/2016
 * Time: 01:22 PM
 */

namespace ParkillerDemo\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use ParkillerDemo\Entities\Location;

class LocationRepository extends EloquentRepository
{

    /**
     * @var ClientRepository
     */
    private $client;


    /**
     * LocationRepository constructor.
     *
     * @param ClientRepository $client
     */
    public function __construct(ClientRepository $client)
    {
        $this->client = $client;
    }


    /**
     * @return Model
     */
    public function getModel()
    {
        return new Location();
    }


    public function getClient($request)
    {
        $coordinates = array_get($request, 'position');

        return $this->getNearestCustomer($coordinates);
    }


    public function getNearestCustomer($origin)
    {
        extract($origin);
        $clients = DB::table('clients')->select(DB::raw("id, (6371 * ACOS( COS(RADIANS($lat)) * COS(RADIANS(latitude)) * 
            COS(RADIANS(longitude) - RADIANS($lng)) + SIN(RADIANS($lat)) * SIN(RADIANS(latitude)))) 
            AS distance"))->having('distance', '<', 1)->orderBy('distance', 'ASC')->get();

        $client = head($clients);
        
        return $this->client->findOrFail($client->id);
    }
}