<?php

//cargando clases
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\ApiAuthMiddleware;//agrego esta linea para que funcione la ruta

Route::get('/', function () {
    return view('welcome');
});
Route::get('/pruebas', function(){
    return'<h2>Texto de una ruta</h2>';
});
//rutas del controlador de usuario (UserController)
Route::post('/api/register',[App\Http\Controllers\UserController::class,'register']);
//Route::post('/pruebas',[App\Http\Controllers\UserController::class,'pruebas']);
Route::post('/api/delete',[App\Http\Controllers\AccionController::class,'delete']);
Route::post('/api/user/login',[App\Http\Controllers\UserController::class,'login']);
Route::put('/api/user/update',[App\Http\Controllers\UserController::class,'update']);
Route::post('/api/user/upload',[App\Http\Controllers\UserController::class,'upload'])->middleware(ApiAuthMiddleware::class);
Route::get('/api/user/avatar/{filename}',[App\Http\Controllers\UserController::class,'getImage']);//se modifica y se agrega el {filename}
Route::get('/api/user/detail/{id}',[App\Http\Controllers\UserController::class,'detail']);//se modifica y se agrega el {id}

//rutas del controlador de clases de vehiculo (vehiculoController)
Route::post('/api/update',[App\Http\Controllers\VehiculoController::class,'update']);
Route::post('/api/store',[App\Http\Controllers\VehiculoController::class,'store']);
Route::post('/api/show',[App\Http\Controllers\VehiculoController::class,'show']);
Route::post('/api/index',[App\Http\Controllers\VehiculoController::class,'index']);
Route::post('/api/destroy',[App\Http\Controllers\VehiculoController::class,'destroy']);
Route::post('/api/getAllVehiculo',[App\Http\Controllers\TipoVehiculoController::class,'getAllVehiculos']);
//rutas del controlador de tipos de vehiculo (TipoVehiculoCOntroller)
Route::post('/api/tipo/update',[App\Http\Controllers\TipoVehiculoController::class,'update']);
Route::post('/api/tipo/store',[App\Http\Controllers\TipoVehiculoController::class,'store']);
Route::post('/api/tipo/show',[App\Http\Controllers\TipoVehiculoController::class,'show']);
Route::post('/api/tipo/index',[App\Http\Controllers\TipoVehiculoController::class,'index']);
Route::post('/api/tipo/destroy',[App\Http\Controllers\TipoVehiculoController::class,'destroy']);
Route::post('/api/tipo/getAllVehiculo',[App\Http\Controllers\TipoVehiculoController::class,'getAllVehiculos']);

//rutas del controlador de entradas en documentos de los vehiculos
/* Route::post('/api/doc/update',[App\Http\Controllers\DocVehiculoController::class,'update']);
Route::post('/api/doc/store',[App\Http\Controllers\DocVehiculoController::class,'store']);
Route::post('/api/doc/show',[App\Http\Controllers\DocVehiculoController::class,'show']);
Route::post('/api/doc/index',[App\Http\Controllers\DocVehiculoController::class,'index']);
Route::post('/api/doc/destroy',[App\Http\Controllers\DocVehiculoController::class,'destroy']);
Route::post('/api/doc/getAllVehiculo',[App\Http\Controllers\TipoVehiculoController::class,'getAllVehiculos']);
 */


//rutas del tDocumentos o tipo de documentos
Route::post('/api/tdoc/update',[App\Http\Controllers\tdocumentosController::class,'update']);
Route::post('/api/tdoc/store',[App\Http\Controllers\tdocumentosController::class,'store']);
Route::post('/api/tdoc/show',[App\Http\Controllers\tdocumentosController::class,'show']);
Route::post('/api/tdoc/index',[App\Http\Controllers\tdocumentosController::class,'index']);
Route::post('/api/tdoc/destroy',[App\Http\Controllers\tdocumentosController::class,'destroy']);
Route::post('/api/tdoc/getAllVehiculo',[App\Http\Controllers\TipoVehiculoController::class,'getAllVehiculos']);


//rutas de las listas 
Route::post('/api/listas/update', [App\Http\Controllers\listasController::class,'update']);
Route::post('/api/listas/store',[App\Http\Controllers\listasController::class,'store']);
Route::post('/api/listas/show',[App\Http\Controllers\listasController::class,'show']);
Route::post('/api/listas/index',[App\Http\Controllers\listasController::class,'index']);
Route::post('/api/listas/destroy',[App\Http\Controllers\listasController::class,'destroy']);
Route::post('/api/listas/getAllVehiculo',[App\Http\Controllers\TipoVehiculoController::class,'getAllVehiculos']);
Route::get('/api/listas/buscador',[App\Http\Controllers\listasController::class,'buscador']);
Route::post('/api/listas/getAllDocumentos',[App\Http\Controllers\listasController::class,'getAllDocumentos']);