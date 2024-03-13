<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });


// $router->get('/api/data', function () {
//     return response()->json(['message' => 'Hello from Lumen API']);
// });

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post("register-user", 'UserController@register');
    $router->post("login", 'UserController@login');
    $router->post("logout", 'UserController@logout');
    $router->get("me", "UserController@me");

    //barang
    $router->post("/barang/create", "BarangController@create");
    $router->get("/barang", "BarangController@index");
    $router->get("/barang/test", "BarangController@test");
    $router->get("/barang/filter", "BarangController@filter");
    $router->get("/barang/{id}", "  BarangController@show");
    $router->post("/barang/update/{id}", "BarangController@update");
    $router->get("/barang/destroy/{id}", "BarangController@destroy");


    //dokumen
    $router->get("/docs", "DokumenController@index");
    $router->get("/docs/filter", "DokumenController@filter");
    $router->post("/docs/create", "DokumenController@create");
    $router->get("/docs/{id}", "DokumenController@show");
    $router->post("/docs/update/{id}", "DokumenController@update");
    $router->post("/docs/destroy/{id}", "DokumenController@destroy");
});

// $router->get('/{any:.*}', function () use ($router) {
//     return view('index');
// });