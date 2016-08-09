<?php

namespace ParkillerDemo\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use ParkillerDemo\Entities\Driver;
use ParkillerDemo\Http\Controllers\Controller;

class DriverController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function updatePosition(Request $request, $id)
    {
        /** @var Driver $driver */
        $driver            = Driver::findOrFail($id);
        $driver->latitude  = $request->get('latitude');
        $driver->longitude = $request->get('longitude');
        $saved             = $driver->saveOrFail();

        if ($saved) {
            // Trigger Event
            $pusher = App::make('pusher');

            $pusher->trigger('test_channel', 'my_event', [
                'id'        => $driver->id,
                'latitude'  => $driver->latitude,
                'longitude' => $driver->longitude
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
