<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$api = app('Dingo\Api\Routing\Router');

$apiParametersV1 = [
    'namespace' => 'App\Http\Controllers\V1',
    'prefix' => 'api/v1',
];

$api->version('v1', $apiParametersV1, function ($api) {
    $api->resource('calendar', 'CalendarController');
});

$apiParametersV2 = [
    'namespace' => 'App\Http\Controllers\V2',
    'prefix' => 'api/v2',
];

$api->version('v2', $apiParametersV2, function ($api) {
    $api->get('calendar/search', 'CalendarController@search');
    $api->get('calendar/today', 'CalendarController@today');
    $api->resource('calendar', 'CalendarController');

    $api->resource('guest_list', 'GuestListController');
});
