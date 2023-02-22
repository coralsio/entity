<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('entity_entities', function (Blueprint $table) {
    $table->boolean('reviewable')
        ->after('has_gallery')
        ->default(false);

    $table->boolean('wishlistable')
        ->after('has_gallery')
        ->default(false);
});
