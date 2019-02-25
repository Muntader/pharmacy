<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subusers', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->char('pharmacy_id',36);
            $table->string('email')->unique();
            $table->string('name',50);
            $table->string('password');
            $table->string('image')->nullable();
            $table->tinyInteger('permission')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->foreign('pharmacy_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('subusers');
    }
}
