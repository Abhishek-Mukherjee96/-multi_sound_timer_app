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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_type');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('account_type');
            $table->string('find_us');
            $table->string('image')->nullable();
            $table->string('card_name')->nullable();
            $table->string('card_number')->nullable();
            $table->string('exp_date')->nullable();
            $table->string('security_code')->nullable();
            $table->string('zip_or_postal_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('is_verified')->nullable();
            $table->string('status')->comment("1 = active, 0 = inactive");
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
