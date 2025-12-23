<?php

use Illuminate\Support\Facades\Route;
use Notes\Http\Actions\Create\Action as CreateNote;

Route::group(['prefix' => 'notes'], function () {
    Route::post('/', CreateNote::class);
});
