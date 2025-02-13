<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SqlQueryController;
use App\Http\Controllers\Admin\UserPermissionController;
use App\Http\Controllers\Admin\RolePermissionsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserPermissionController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/{user}/edit', [UserPermissionController::class, 'edit'])->name('admin.users.edit');
    Route::post('/admin/users/{user}/update', [UserPermissionController::class, 'update'])->name('admin.users.update');

    Route::get('/admin/roles-permissions', [RolePermissionsController::class, 'index'])->name('admin.roles-permissions.index');

    Route::post('/admin/roles', [RolePermissionsController::class, 'storeRole'])->name('admin.roles.store');
    Route::delete('/admin/roles/{role}', [RolePermissionsController::class, 'deleteRole'])->name('admin.roles.delete');

    Route::post('/admin/permissions', [RolePermissionsController::class, 'storePermission'])->name('admin.permissions.store');
    Route::delete('/admin/permissions/{permission}', [RolePermissionsController::class, 'deletePermission'])->name('admin.permissions.delete');
});


Route::middleware(['auth', 'permission:admin'])->group(function () {
    Route::get('/dev', [SqlQueryController::class, 'index'])->name('admin.dev');
    Route::post('/dev/execute', [SqlQueryController::class, 'execute'])->name('admin.dev.execute');
    Route::post('/dev/export-csv', [SqlQueryController::class, 'exportCsv'])->name('admin.dev.export-csv');
    Route::post('/dev/export-json', [SqlQueryController::class, 'exportJson'])->name('admin.dev.export-json');
});

require __DIR__.'/auth.php';
