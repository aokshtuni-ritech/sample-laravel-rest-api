<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Admin\AdminEmployeeController;
use App\Http\Controllers\EntityMappingController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {

        Route::post('logout', [AuthController::class, 'logout']);

        Route::middleware(['is-user'])
            ->group(function () {
                Route::resource('employees', EmployeeController::class);
            });

        Route::middleware(['is-admin'])
            ->prefix('admin')
            ->group(function () {
                Route::get('employees', [AdminEmployeeController::class, 'getAll']);
                Route::post('integration/{integration}/set-token', [IntegrationController::class, 'setToken']);
                Route::resource('entity-mappings', EntityMappingController::class);
                Route::resource('users', UserController::class);
        });
    });
});
