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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('gym_id');
            $table->unsignedBigInteger('buyable_ticket_id');
            $table->enum('type', ['one-time', 'monthly']);
            $table->date('expiration');
            $table->date('bought');
            $table->string('code')->unique();
            $table->timestamps();

            $table->foreign('buyable_ticket_id')->references('id')->on('buyable_tickets');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('tickets');
    }
};
