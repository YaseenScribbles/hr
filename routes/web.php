<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Loading');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', function () {
        return inertia('Dashboard');
    })->name('dashboard');

    Route::apiResource('users', \App\Http\Controllers\UserController::class);
    Route::apiResource('companies', \App\Http\Controllers\CompanyController::class);
    Route::apiResource('departments', \App\Http\Controllers\DepartmentController::class);
    Route::apiResource('categories', \App\Http\Controllers\CategoryController::class);
    Route::apiResource('designations', \App\Http\Controllers\DesignationController::class);

    Route::apiResource('employees', \App\Http\Controllers\EmployeeController::class);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', function () {
        return inertia('Login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});


