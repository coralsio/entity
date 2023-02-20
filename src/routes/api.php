<?php

Route::group(['prefix' => 'entity', 'as' => 'entity.'], function () {
    Route::apiResource('entities', 'EntitiesController');
    Route::apiResource('entities.entries', 'EntriesController');
});
