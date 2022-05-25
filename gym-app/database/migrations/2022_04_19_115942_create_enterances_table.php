<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterances', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('locker_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('gym_id');

            $table->date('enter');
            $table->date('exit')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->foreign('gym_id')->references('id')->on('gyms');
            $table->foreign('locker_id')->references('id')->on('lockers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enterances');
    }
};
