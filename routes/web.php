<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('/test-show-image/{path}/{image}.{type}', 'Genetsis\Promotions\Controllers\ShowImage@index');

    Route::prefix('admin')->group(function () {
        //Route::namespace('Genetsis\Promotions\Controllers')->group(function () {

        Route::group(['namespace' => 'Genetsis\Promotions\Controllers'], function () {
            Route::resource('campaigns', 'CampaignsController', [
                'only' => ['index', 'show', 'edit', 'create', 'store', 'update', 'destroy'],
                'names' => ['index' => 'campaigns.home']
            ]);

            Route::resource('promotions', 'PromotionsController', [
                'only' => ['index', 'show', 'edit', 'create', 'store', 'update', 'destroy'],
                'names' => ['index' => 'promotions.home']
            ]);

        });
    });
});


