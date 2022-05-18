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
        Schema::create('buyable_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gym_id');
            $table->string('type', 25);
            $table->string('name', 45);
            $table->text('description');
            $table->integer('quantity');
            $table->integer('price');
            $table->boolean('hidden');
            $table->timestamps();

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
        Schema::dropIfExists('buyable_tickets');
    }
};
