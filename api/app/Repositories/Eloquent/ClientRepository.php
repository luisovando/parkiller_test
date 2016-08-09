<?php
/**
 * Created by PhpStorm.
 * User: Desarrollo
 * Date: 08/08/2016
 * Time: 01:37 AM
 */

namespace ParkillerDemo\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use ParkillerDemo\Entities\Client;

class ClientRepository extends EloquentRepository
{

    /**
     * @return Model
     */
    public function getModel()
    {
        return new Client();
    }
}