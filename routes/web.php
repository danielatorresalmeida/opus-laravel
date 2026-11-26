<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComposerController;
use App\Http\Controllers\ComposerFavoriteController;
use App\Http\Controllers\CompositionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OpenOpusController;

Route::redirect('/', '/catalogue');

Route::get('/catalogue', [ComposerController::class, 'index'])
    ->name('composers.index');
Route::get('/composers/{composer}', [ComposerController::class, 'show'])
    ->whereNumber('composer')->name('composers.show');
Route::get('/compositions/{composition}', [CompositionController::class, 'show'])
    ->whereNumber('composition')->name('compositions.show');
Route::view('/sources', 'sources.index')->name('sources.index');

Route::middleware('auth')->group(function () {
    Route::get('/composers/{composer}/edit', [ComposerController::class, 'edit'])
        ->whereNumber('composer')->name('composers.edit');
    Route::put('/composers/{composer}', [ComposerController::class, 'update'])
        ->whereNumber('composer')->name('composers.update');

    Route::get('/compositions/{composition}/edit', [CompositionController::class, 'edit'])
        ->whereNumber('composition')->name('compositions.edit');
    Route::put('/compositions/{composition}', [CompositionController::class, 'update'])
        ->whereNumber('composition')->name('compositions.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/compositions/{composition}/favorite', [FavoriteController::class, 'store'])
        ->whereNumber('composition')->name('favorites.store');
    Route::delete('/compositions/{composition}/favorite', [FavoriteController::class, 'destroy'])
        ->whereNumber('composition')->name('favorites.destroy');

    Route::post('/composers/{composer}/favorite', [ComposerFavoriteController::class, 'store'])
        ->whereNumber('composer')->name('composer-favorites.store');
    Route::delete('/composers/{composer}/favorite', [ComposerFavoriteController::class, 'destroy'])
        ->whereNumber('composer')->name('composer-favorites.destroy');
});

Route::middleware(['auth', 'can:manage-system'])->group(function () {
    Route::get('/admin/openopus', [OpenOpusController::class, 'index'])
        ->name('openopus.index');
    Route::post('/admin/openopus/composers', [OpenOpusController::class, 'importComposers'])
        ->name('openopus.composers.import');
    Route::get('/admin/openopus/composers/{composer}/works', [OpenOpusController::class, 'works'])
        ->whereNumber('composer')->name('openopus.works');
    Route::post('/admin/openopus/composers/{composer}/works', [OpenOpusController::class, 'importWorks'])
        ->whereNumber('composer')->name('openopus.works.import');

    Route::get('/composers/create', [ComposerController::class, 'create'])
        ->name('composers.create');
    Route::post('/composers', [ComposerController::class, 'store'])
        ->name('composers.store');
    Route::delete('/composers/{composer}', [ComposerController::class, 'destroy'])
        ->whereNumber('composer')->name('composers.destroy');

    Route::get('/composers/{composer}/compositions/create', [CompositionController::class, 'create'])
        ->whereNumber('composer')->name('composers.compositions.create');
    Route::post('/composers/{composer}/compositions', [CompositionController::class, 'store'])
        ->whereNumber('composer')->name('composers.compositions.store');
    Route::delete('/compositions/{composition}', [CompositionController::class, 'destroy'])
        ->whereNumber('composition')->name('compositions.destroy');
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
