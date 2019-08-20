<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('/test-show-image/{path}/{image}.{type}', 'Genetsis\Promotions\Controllers\ShowImage@index');
    Route::get('/download-sample/{file}', 'Genetsis\Promotions\Controllers\DownloadSample@index')->name('download-sample');

    Route::prefix('admin')->group(function () {
        //Route::namespace('Genetsis\Promotions\Controllers')->group(function () {

        Route::group(['namespace' => 'Genetsis\Promotions\Controllers'], function () {
            Route::resource('campaigns', 'CampaignsController', [
                'only' => ['index', 'show', 'edit', 'create', 'store', 'update', 'destroy'],
                'names' => ['index' => 'campaigns.home']
            ]);
            Route::get('campaigns/{id}/refresh', 'CampaignsController@refresh')->name('campaign.refresh');

            Route::get('promotions/get', 'PromotionsController@get')->name('promotions.api');

            Route::resource('promotions', 'PromotionsController', [
                'only' => ['index', 'show', 'edit', 'create', 'store', 'update', 'destroy'],
                'names' => ['index' => 'promotions.home']
            ]);
        });
    });
});



