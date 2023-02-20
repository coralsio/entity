<?php

namespace Corals\Modules\Entity\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EntityTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_entities', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code');
            $table->string('name_singular');
            $table->string('name_plural');

            $table->text('fields');

            $table->boolean('has_tags')->default(false);
            $table->boolean('has_gallery')->default(false);

            $table->boolean('reviewable')->default(false);
            $table->boolean('wishlistable')->default(false);

            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();


            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('entity_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('entity_id')->index();

            $table->text('values')->nullable();
            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->foreign('entity_id')
                ->references('id')
                ->on('entity_entities')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entity_entries');
        Schema::dropIfExists('entity_entities');
    }
}
