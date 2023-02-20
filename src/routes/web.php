<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'entity', 'as' => 'entity.'], function () {
    Route::post('entry/wishlist/{entry}', 'EntryWishlistsController@setWishlist');
    Route::post('entry/{entry}/create-rate', 'EntryReviewsController@createRating');

    Route::resource('entities', 'EntitiesController');
    Route::resource('entities.entries', 'EntriesController');
});
