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
        Schema::create('timer_segments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timer_id');
            $table->string('segment_name');
            $table->string('duration');
            $table->string('end_sound');
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
        Schema::dropIfExists('timer_segments');
    }
};
