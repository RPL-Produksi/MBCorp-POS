<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdmin\Kelola\SuperAdminOwnerController;
use App\Http\Controllers\SuperAdmin\Kelola\SuperAdminPerusahaanController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::redirect('/', '/login');
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'indexLogin')->name('login');
    Route::post('/login', 'login')->name('post.login');
    Route::post('/change-just-password/{id}', 'changeJustPassword')->name('change.just.password');
});

Route::middleware('auth')->group(function () {
    Route::group(['prefix' => 'superadmin', 'middleware' => 'can:superadmin'], function () {
        Route::controller(SuperAdminDashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('superadmin.dashboard');
        });

        Route::prefix('kelola')->group(function () {
            Route::prefix('perusahaan')->group(function () {
                Route::controller(SuperAdminPerusahaanController::class)->group(function () {
                    Route::get('/', 'index')->name('superadmin.kelola.perusahaan');
                    Route::get('/form/{id?}', 'form')->name('superadmin.kelola.perusahaan.form');
                    Route::post('/form/store/{id?}', 'store')->name('superadmin.kelola.perusahaan.store');
                    Route::get('/data', 'data')->name('superadmin.kelola.perusahaan.data');
                    Route::delete('/{id}/delete', 'delete')->name('superadmin.kelola.perusahaan.delete');
                });

                Route::prefix('{perusahaanId}')->group(function () {
                    Route::prefix('owner')->group(function () {
                        Route::controller(SuperAdminOwnerController::class)->group(function () {
                            Route::get('/', 'index')->name('superadmin.kelola.perusahaan.owner');
                            Route::post('/store/{ownerId?}', 'store')->name('superadmin.kelola.perusahaan.owner.store');
                            Route::get('/data', 'data')->name('superadmin.kelola.perusahaan.owner.data');
                            Route::get('/data/{ownerId}', 'dataById')->name('superadmin.kelola.perusahaan.owner.data.id');
                            Route::delete('/{ownerId}/delete', 'delete')->name('superadmin.kelola.perusahaan.owner.data.delete');
                        });
                    });
                });
            });
        });
    });

    Route::group(['prefix' => 'admin', 'middleware' => 'can:admin'], function () {
        Route::controller(AdminDashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('admin.dashboard');
        });
    });
});
