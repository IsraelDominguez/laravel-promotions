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

        Route::get('promotion/{id}/moments', 'Genetsis\Promotions\Controllers\PromotionsController@moments');
        Route::get('promotion/{id}/pincodes', 'Genetsis\Promotions\Controllers\PromotionsController@pincodes');

        Route::prefix('entrypoint')->group(function () {
            Route::get('list/{campaign_id}', 'Genetsis\Promotions\Controllers\PromotionsController@getEntrypoints')->name('campaign.entrypoints');
        });
    });


});


