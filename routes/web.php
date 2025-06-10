<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ASBController;
use App\Http\Controllers\CostDriverController;
use App\Http\Controllers\SKPDController;
use App\Http\Controllers\ObjekBelanjaController;
use App\Http\Controllers\KalkulatorASBController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BukuManualController;

Auth::routes();

Route::middleware('auth')->group(function () {

    Route::get('/', [App\Http\Controllers\KalkulatorASBController::class, 'index'])
        ->middleware('auth')
        ->name('dashboard');

    Route::get('struktur-asb/{asb}/breakdown', [KalkulatorASBController::class, 'breakdown'])
        ->name('asb.breakdown');

    Route::controller(SKPDController::class)
        ->prefix('data-skpd')
        ->name('data-skpd.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

    Route::controller(ASBController::class)
        ->prefix('struktur-asb')
        ->name('asb.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::post('/{asb}/riwayat', 'riwayatStore')->name('riwayat.store');
            Route::delete('/{asb}/riwayat/{tahun}', 'riwayatDestroyTahun')->name('riwayat.destroy.tahun');
            Route::get('asb-options', 'getOptions')->name('options');
        });

    Route::prefix('struktur-asb/{struktur_asb}')
        ->name('asb.')
        ->middleware('auth')
        ->group(function () {
            Route::get('cost-driver', [CostDriverController::class, 'index'])->name('cost-driver.index');
            Route::get('cost-driver/create', [CostDriverController::class, 'create'])->name('cost-driver.create');
            Route::post('cost-driver', [CostDriverController::class, 'store'])->name('cost-driver.store');
            Route::get('cost-driver/{id}/edit', [CostDriverController::class, 'edit'])->name('cost-driver.edit');
            Route::put('cost-driver/{id}', [CostDriverController::class, 'update'])->name('cost-driver.update');
            Route::delete('cost-driver/{id}', [CostDriverController::class, 'destroy'])->name('cost-driver.destroy');
        });

    Route::controller(ObjekBelanjaController::class)
        ->prefix('objek-belanja')
        ->name('objek.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

    Route::controller(UserController::class)
        ->prefix('users')
        ->name('users.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/change-password', 'changePassword')->name('change.password');
            Route::post('/update-password', 'updatePassword')->name('update.password');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{user}/edit', 'edit')->name('edit');
            Route::put('/{user}', 'update')->name('update');
            Route::delete('/{user}', 'destroy')->name('destroy');
            Route::get('/{user}/reset-password', 'resetPassword')->name('resetPassword');
        });

    Route::controller(BukuManualController::class)
        ->group(function () {
            // Admin: Upload Buku Manual
            Route::get('upload-buku-panduan', 'create')->name('buku-manual.upload.form');
            Route::post('upload-buku-panduan', 'store')->name('buku-manual.upload');

            // User: View Buku Manual
            Route::get('buku-panduan', 'index')->name('buku-manual.index');
        });
});
