<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Kelola\AdminKasirController;
use App\Http\Controllers\Admin\Kelola\AdminOwnerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\Kasir\Kelola\KasirBarangController;
use App\Http\Controllers\Kasir\Kelola\KasirKategoriController;
use App\Http\Controllers\SuperAdmin\Kelola\SuperAdminAdminController;
use App\Http\Controllers\SuperAdmin\Kelola\SuperAdminKasirController;
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
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
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
                            Route::delete('/{ownerId}/delete', 'delete')->name('superadmin.kelola.perusahaan.owner.delete');
                        });
                    });

                    Route::prefix('admin')->group(function () {
                        Route::controller(SuperAdminAdminController::class)->group(function () {
                            Route::get('/', 'index')->name('superadmin.kelola.perusahaan.admin');
                            Route::post('/store/{adminId?}', 'store')->name('superadmin.kelola.perusahaan.admin.store');
                            Route::get('/data', 'data')->name('superadmin.kelola.perusahaan.admin.data');
                            Route::get('/data/{adminId}', 'dataById')->name('superadmin.kelola.perusahaan.admin.data.id');
                            Route::delete('/{adminId}/delete', 'delete')->name('superadmin.kelola.perusahaan.admin.delete');
                        });
                    });

                    Route::prefix('kasir')->group(function () {
                        Route::controller(SuperAdminKasirController::class)->group(function () {
                            Route::get('/', 'index')->name('superadmin.kelola.perusahaan.kasir');
                            Route::post('/store/{kasirId?}', 'store')->name('superadmin.kelola.perusahaan.kasir.store');
                            Route::get('/data', 'data')->name('superadmin.kelola.perusahaan.kasir.data');
                            Route::get('/data/{kasirId}', 'dataById')->name('superadmin.kelola.perusahaan.kasir.data.id');
                            Route::delete('/{kasirId}/delete', 'delete')->name('superadmin.kelola.perusahaan.kasir.delete');
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

        Route::prefix('kelola')->group(function () {
            Route::prefix('owner')->group(function () {
                Route::controller(AdminOwnerController::class)->group(function () {
                    Route::get('/', 'index')->name('admin.kelola.owner');
                    Route::post('/store/{id?}', 'store')->name('admin.kelola.owner.store');
                    Route::get('/data', 'data')->name('admin.kelola.owner.data');
                    Route::get('/data/{id}', 'dataById')->name('admin.kelola.owner.data.id');
                    Route::delete('/{id}/delete', 'delete')->name('admin.kelola.owner.delete');
                });
            });

            Route::prefix('kasir')->group(function () {
                Route::controller(AdminKasirController::class)->group(function () {
                    Route::get('/', 'index')->name('admin.kelola.kasir');
                    Route::post('/store/{id?}', 'store')->name('admin.kelola.kasir.store');
                    Route::get('/data', 'data')->name('admin.kelola.kasir.data');
                    Route::get('/data/{id}', 'dataById')->name('admin.kelola.kasir.data.id');
                    Route::delete('/{id}/delete', 'delete')->name('admin.kelola.kasir.delete');
                });
            });
        });
    });

    Route::group(['prefix' => 'kasir', 'middleware' => 'can:kasir'], function () {
        Route::controller(KasirDashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('kasir.dashboard');
            Route::get('/dashboard/data', 'data')->name('kasir.dashboard.data');
            Route::get('/dashboard/keranjang/data', 'keranjangData')->name('kasir.dashboard.keranjang.data');
            Route::post('/dashboard/produk/{id}/keranjang', 'tambahKeranjang')->name('kasir.dashboard.keranjang.add');
            Route::delete('/dashboard/keranjang/{id}/delete', 'hapusKeranjang')->name('kasir.dashboard.keranjang.delete');
        });

        Route::prefix('kelola')->group(function () {
            Route::prefix('kategori')->group(function () {
                Route::controller(KasirKategoriController::class)->group(function () {
                    Route::get('/', 'index')->name('kasir.kelola.kategori');
                    Route::post('/store/{id?}', 'store')->name('kasir.kelola.kategori.store');
                    Route::get('/data', 'data')->name('kasir.kelola.kategori.data');
                    Route::get('/data/{id}', 'dataById')->name('kasir.kelola.kategori.data.id');
                    Route::delete('/{id}/delete', 'delete')->name('kasir.kelola.kategori.delete');
                });
            });

            Route::prefix('barang')->group(function () {
                Route::controller(KasirBarangController::class)->group(function () {
                    Route::get('/', 'index')->name('kasir.kelola.barang');
                    Route::post('/store/{id?}', 'store')->name('kasir.kelola.barang.store');
                    Route::post('/stock/add/{id}', 'addStock')->name('kasir.kelola.barang.stock.add');
                    Route::get('/data', 'data')->name('kasir.kelola.barang.data');
                    Route::get('/data/{id}', 'dataById')->name('kasir.kelola.barang.data.id');
                    Route::delete('/{id}/delete', 'delete')->name('kasir.kelola.barang.delete');
                    Route::post('/image/change/{id}', 'changeImage')->name('kasir.kelola.barang.change.image');
                });
            });
        });
    });
});
