<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group([ "prefix" => "v1", "namespace" => "v1", "middleware" => "cors" ],
    function (\Illuminate\Routing\Router $api) {
        $api->post('register', 'RegisterController@store');
        $api->put('drivers/{id}/move', 'DriverController@updatePosition');
        $api->resource('clients', 'ClientController');
        $api->put('clients/{id}/move', 'ClientController@updatePosition');
        $api->resource('travels', 'TravelController');
        $api->resource('trackings', 'TrackingController');
        $api->post('locations/client', 'LocationController@store');
    });
