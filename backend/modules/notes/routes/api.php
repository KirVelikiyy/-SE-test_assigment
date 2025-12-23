<?php

use Illuminate\Support\Facades\Route;
use Notes\Http\Actions\Create\Action as CreateNote;
use Notes\Http\Actions\Delete\Action as DeleteNote;

Route::group(['prefix' => 'notes'], function () {
    Route::post('/', CreateNote::class);
    Route::delete('/{note}', DeleteNote::class);
});
