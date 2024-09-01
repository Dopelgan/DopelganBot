<?php

use App\Http\Controllers\DutyScheduleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuardController;
use App\Http\Controllers\DepartmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->middleware('role:ADMIN')->group(function () {
    Route::get('/guards', [GuardController::class, 'index'])->name('guards.index');
    Route::get('/guards/create', [GuardController::class, 'create'])->name('guards.create');
    Route::post('/guards', [GuardController::class, 'store'])->name('guards.store');
    Route::get('/guards/{id}/edit', [GuardController::class, 'edit'])->name('guards.edit');
    Route::put('/guards/{id}', [GuardController::class, 'update'])->name('guards.update');
    Route::delete('/guards/{id}', [GuardController::class, 'destroy'])->name('guards.destroy');

    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{id}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    Route::get('/dutySchedule/{id}/edit-monthly-duties', [DutyScheduleController::class, 'editMonthlyDuties'])->name('dutySchedule.edit_monthly_duties');
    Route::put('/dutySchedule/{id}/update-monthly-duties', [DutyScheduleController::class, 'updateMonthlyDuties'])->name('dutySchedule.update_monthly_duties');

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
