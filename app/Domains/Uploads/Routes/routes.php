<?php

Route::domain('api.'.Config::get('app.url'))->group(function () {
    Route::get(
        'v1/uploads/export',
        'App\Domains\Uploads\Controllers\ExportController@index'
    )->middleware('api');

    Route::resource(
        'v1/uploads',
        \App\Domains\Uploads\Controllers\UploadsController::class
    )->middleware('api');

    Route::put(
        'v1/uploads/{upload}/views',
        'App\Domains\Uploads\Controllers\ViewsCounterController@update'
    )->middleware('api');

    Route::put(
        'v1/uploads/{upload}/downloads',
        'App\Domains\Uploads\Controllers\DownloadsCounterController@update'
    )->middleware('api');
});
