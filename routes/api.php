<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function(){
    Route::post('/hyp2000',         ['uses' => 'App\Api\v1\Controllers\Hyp2000Controller@query', 'middleware' => ['App\Api\Middleware\JsonApiMiddleware']]);
    Route::get('/hyp2000stations',  ['uses' => 'App\Api\v1\Controllers\Hyp2000StationsController@query', 'middleware' => ['App\Api\Middleware\UniformInputParametersMiddleware']]);
});