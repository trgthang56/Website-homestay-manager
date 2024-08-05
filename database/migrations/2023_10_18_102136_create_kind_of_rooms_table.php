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
        Schema::create('kind_of_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kind_of_room',100);
            $table->integer('available');
            $table->integer('total');
            $table->string('description');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kind_of_rooms');
    }
};
