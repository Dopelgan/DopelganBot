<?php

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
    // Роут для отображения формы редактирования ежемесячного расписания дежурных
    Route::get('/guards/edit-monthly-duties', [GuardController::class, 'editMonthlyDuties'])
        ->name('guards.edit_monthly_duties')
        ->middleware('auth'); // Потребуется аутентификация, если это админская часть
    // Роут для обработки обновления данных дежурных
    Route::post('/guards/update-monthly-duties', [GuardController::class, 'updateMonthlyDuties'])
        ->name('guards.update_monthly_duties')
        ->middleware('auth'); // Потребуется аутентификация, если это админская часть

});


Route::get('/admin/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::get('/admin/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
Route::post('/admin/departments', [DepartmentController::class, 'store'])->name('departments.store');
Route::get('/admin/departments/{id}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
Route::put('/admin/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
Route::delete('/admin/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
