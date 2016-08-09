<?php
/**
 * Created by PhpStorm.
 * User: Desarrollo
 * Date: 07/08/2016
 * Time: 05:54 PM
 */

namespace ParkillerDemo\Repositories\Eloquent;

use Carbon\Carbon;
use DB;
use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use ParkillerDemo\Entities\Client;
use ParkillerDemo\Entities\Driver;
use ParkillerDemo\Entities\Register;
use ParkillerDemo\helpers\TruncateTrait;

class RegisterRepository extends EloquentRepository
{

    use TruncateTrait;

    protected $marker = [ "latitude" => 19.4340200, "longitude" => -99.1956010 ];

    /**
     * @var ClientRepository
     */
    private $client;

    /**
     * @var DriverRepository
     */
    private $driver;


    /**
     * RegisterRepository constructor.
     *
     * @param ClientRepository $client
     * @param DriverRepository $driver
     */
    public function __construct(ClientRepository $client, DriverRepository $driver)
    {
        $this->client = $client;
        $this->driver = $driver;
    }


    /**
     * @return Model
     */
    public function getModel()
    {
        return new Register();
    }


    /**
     * @param $request
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function create($request)
    {
        $this->truncateTables();

        return $this->setMarkers($request);
    }


    private function setMarkers($request)
    {
        $markers_clients = [ ];
        $markers_drivers = [ ];
        $faker           = Factory::create();
        $latitude        = array_get($this->marker, "latitude");
        $longitude       = array_get($this->marker, "longitude");

        foreach ($request as $type => $quantity) {

            for ($i = 0; $i < $quantity; $i++) {
                $marker = [
                    "name"       => $faker->firstName,
                    "last_name"  => $faker->lastName,
                    "latitude"   => $latitude + ( $this->random() / 100 ),
                    "longitude"  => $longitude + ( $this->random() / 100 ),
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ];

                if ($type == 'client') {
                    array_push($markers_clients, $marker);
                } else {
                    array_push($markers_drivers, $marker);
                }
            }
        }

        $this->client->bulkInsert($markers_clients);
        $this->driver->bulkInsert($markers_drivers);

        return $this->getMarkers();
    }


    private function getMarkers()
    {
        $clients = Client::all()->toArray();
        $drivers = Driver::all()->toArray();

        return array_merge($clients, $drivers);
    }


    private function random()
    {
        return (float) rand() / (float) getrandmax();
    }
}