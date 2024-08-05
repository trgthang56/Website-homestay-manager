<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_kind_of_room')->unsigned();
            $table->string('name',100);
            $table->integer('number');
            $table->integer('surface');
            $table->integer('capacity');
            $table->integer('bed');
            $table->string('status');
            $table->float('price', 255);
            $table->string('room_amenity', 255);
            $table->string('bathroom_amenity', 255);
            $table->string('description', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
