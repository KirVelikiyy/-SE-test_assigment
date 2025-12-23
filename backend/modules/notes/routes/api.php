<?php

use Illuminate\Support\Facades\Route;
use Notes\Http\Actions\Create\Action as CreateNote;
use Notes\Http\Actions\Delete\Action as DeleteNote;
use Notes\Http\Actions\GetAll\Action as GetAllNotes;
use Notes\Http\Actions\GetOne\Action as GetNote;
use Notes\Http\Actions\Update\Action as UpdateNote;

Route::group(['prefix' => 'notes'], function () {
    Route::post('/', CreateNote::class);
    Route::get('/', GetAllNotes::class);
    Route::get('/{note}', GetNote::class);
    Route::put('/{note}', UpdateNote::class);
    Route::delete('/{note}', DeleteNote::class);
});
