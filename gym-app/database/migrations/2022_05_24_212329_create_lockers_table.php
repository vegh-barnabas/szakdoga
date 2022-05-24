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
        Schema::create('lockers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gym_id');
            $table->integer('number');
            $table->enum('gender', ['male', 'female']);
            $table->timestamps();

            $table->unique(['gym_id', 'number']);

            $table->foreign('gym_id')->references('id')->on('gyms');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lockers');
    }
};
