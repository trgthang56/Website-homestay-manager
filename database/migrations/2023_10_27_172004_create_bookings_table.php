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
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->integer('id_customer')->unsigned();
            $table->string('id_room')->unsigned();
            $table->string('id_service')->unsigned();
            $table->integer('set_for_other');
            $table->string('name',100);
            $table->string('phone',100);
            $table->string('email',100);
            $table->integer('number_of_guest');
            $table->integer('number_of_room');
            $table->string('total');
            $table->string('special_requirement', 255);
            $table->integer('status');
            $table->dateTime('check_in_date');
            $table->dateTime('check_out_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
