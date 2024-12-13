<?php

use App\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

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
Route::post('/cliente', [ClienteController::class, 'add']);

Route::put('/cliente/{id}', [ClienteController::class, 'updateName']);

Route::get('/cliente/{id}', [ClienteController::class, 'getClient']);

// Route::put('/cliente/{id}', function (Request $request) {
//     $cliente = Cliente::find($id);
//     $cliente->nome = 'Jose';
//     $cliente->save();
//     return response()->json($cliente);
// });

Route::delete('/cliente/{id}', function (Request $request) {
    $cliente = Cliente::find($id);
    $cliente->save();
    return response()->json($cliente);
});


// Route::get('/cliente/{id}', function (Request $request) {
//     getClient
// });

Route::get('/consulta/final-placa/{numero}', function (Request $request) {
    $cliente = Cliente::find($id);
    
    $cliente->save();
    return response()->json($cliente);
});
