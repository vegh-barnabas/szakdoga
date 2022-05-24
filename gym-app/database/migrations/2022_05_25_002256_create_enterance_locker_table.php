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
        Schema::create('enterance_locker', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enterance_id');
            $table->unsignedBigInteger('locker_id')->nullable();
            $table->timestamps();

            $table->unique(['enterance_id', 'locker_id']);
            $table->foreign('enterance_id')->references('id')->on('enterances');
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
        Schema::dropIfExists('enterance_locker');
    }
};
