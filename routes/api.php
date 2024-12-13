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

Route::get('/consulta/final-placa/{numero}', [ClienteController::class, 'consultarPorUltimoNumeroPlaca']);


Route::delete('/cliente/{id}', function (Request $request) {
    $cliente = Cliente::find($request->id);
    if(!$cliente){
        return response()->json(['error' => 'não encontrei o cliente'], 400);
    }
    $cliente->delete();
    return response()->json($cliente);
});



