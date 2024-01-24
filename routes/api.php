<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\OrderController;
use App\Models\User;

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

//user login
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

//get tipe kamar yang tersedia guest
Route::post('/checkroom', [RoomTypeController::class, 'filter']);
Route::get('/roomtype', [RoomTypeController::class, 'show']);
Route::get('/roomtype/{id}', [RoomTypeController::class, 'detail']);
Route::get('/room', [RoomController::class, 'show']);
Route::post('/order', [OrderController::class, 'store']);

//find order
Route::post('/searchorder', [OrderController::class, 'searchorder']);
Route::post('/order/{id}', [OrderController::class, 'detail']);

Route::group(['middleware' => ['jwt.verify']], function(){
    Route::get('/login/check', [UserController::class, 'getAuthenticatedUser']);

    Route::group(['middleware' => ['api.admin']], function(){
        //user
        Route::get('/user', [UserController::class, 'show']);
        Route::get('/user/{id}', [UserController::class, 'detail']);
        Route::put('/user/{id}', [UserController::class, 'update']);
        Route::delete('/user/{id}', [UserController::class, 'destroy']);
        Route::post('/user/image/{id}', [UserController::class, 'uploadImage']);
        
        //room type
        Route::post('/roomtype', [RoomTypeController::class, 'store']);
        Route::put('/roomtype/{id}', [RoomTypeController::class, 'update']);
        Route::delete('/roomtype/{id}', [RoomTypeController::class, 'destroy']);
        Route::post('/roomtype/image/{id}', [RoomTypeController::class, 'uploadImage']);

        //room
        Route::post('/room', [RoomController::class, 'store']);
        Route::put('/room/{id}', [RoomController::class, 'update']);
        Route::delete('/room/{id}', [RoomController::class, 'destroy']);
    });

    Route::group(['middleware' => ['api.receptionist']], function(){
        //order
        Route::get('/order', [OrderController::class, 'show']);
        Route::put('/order/{id}', [OrderController::class, 'status']);
        Route::post('/order/find', [OrderController::class, 'findByName']);
    });
    // Route::post('logout', 'logout');
    // Route::post('me', 'me');
});





