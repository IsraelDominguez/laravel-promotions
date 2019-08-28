<?php

use Illuminate\Http\Request;

Route::group(['middleware' => ['web']], function () {
    Route::prefix('api/v1')->group(function () {
        Route::group(['namespace' => 'Genetsis\Promotions\Controllers'], function () {
            Route::get('participations/{id}', 'ParticipationsController@show');
            Route::delete('participations/delete/{id}', 'ParticipationsController@destroy');
            Route::get('participation/{id}', 'ParticipationsController@get');
            Route::put('participation/{id}', 'ParticipationsController@update');
        });



        Route::prefix('entrypoint')->group(function () {
            Route::get('list/{campaign_id}', 'Genetsis\Promotions\Controllers\Api\EntrypointController@get')->name('campaign.entrypoints');
        });
        Route::prefix('promotion')->group(function () {
            Route::get('{id}/moments', 'Genetsis\Promotions\Controllers\PromotionsController@moments');
            Route::get('{id}/pincodes', 'Genetsis\Promotions\Controllers\PromotionsController@pincodes');
            Route::get('{id}/winners', 'Genetsis\Promotions\Controllers\Api\PromotionsController@winners')->name('promotion.winners');
        });
    });


});


