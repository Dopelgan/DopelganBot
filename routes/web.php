<?php

use App\Http\Controllers\DutyScheduleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DutyController;
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
    Route::get('/duties', [DutyController::class, 'index'])->name('duties.index');
    Route::get('/duties/create', [DutyController::class, 'create'])->name('duties.create');
    Route::post('/duties', [DutyController::class, 'store'])->name('duties.store');
    Route::get('/duties/{id}/edit', [DutyController::class, 'edit'])->name('duties.edit');
    Route::put('/duties/{id}', [DutyController::class, 'update'])->name('duties.update');
    Route::delete('/duties/{id}', [DutyController::class, 'destroy'])->name('duties.destroy');
    Route::post('/duties/upload', [DutyController::class, 'upload'])->name('duties.upload');

    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{id}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    Route::get('/dutySchedules/upload', [DutyScheduleController::class, 'showUploadForm'])->name('dutySchedules.uploadForm');
    Route::post('/dutySchedules/upload', [DutyScheduleController::class, 'upload'])->name('dutySchedules.upload');

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
