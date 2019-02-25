<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('name',70);
            $table->string('username',70)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image')->nullable();
            $table->boolean('confirmed')->default(0);
            $table->string('confirmation_code')->nullable();
            $table->string('forget_params_hash',50)->nullable();
            $table->boolean('is_block')->default(0);
            $table->string('stripe_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('period_end')->nullable();
            $table->integer('status',5)->nullable();       
            $table->string('language',4)->default('en');
            
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
}
