<?php

use App\Http\Controllers\DeputadoController;
use App\Http\Controllers\DespesaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DeputadoController::class, 'getAllApi'])->name('deputados.home');

Route::group(['prefix' => 'deputados', 'as' => 'deputados.'], function () {
    Route::get('/', [DeputadoController::class, 'index'])->name('index');
    Route::post('/sync', [DeputadoController::class, 'sync'])->name('sync');
});
Route::group(['prefix' => 'deputado', 'as' => 'deputado.'], function () {
    Route::get('/{id}', [DeputadoController::class, 'show'])->name('show');
    Route::delete('/{id}', [DeputadoController::class, 'destroy'])->name('destroy');
});

Route::group(['prefix' => 'despesas', 'as' => 'despesas.'], function () {
    Route::get('/', [DespesaController::class, 'index'])->name('index');
    Route::post('/sync', [DespesaController::class, 'sync'])->name('sync');
});
Route::group(['prefix' => 'despesa', 'as' => 'despesa.'], function () {
    Route::get('/{id}', [DespesaController::class, 'show'])->name('show');
    Route::delete('/{id}', [DespesaController::class, 'destroy'])->name('destroy');
});
