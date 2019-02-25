<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {

            $table->increments('id');
            $table->string('customer_id',20);
            $table->unique('customer_id');
            $table->string('customer_name', 70);
            $table->string('customer_address', 50)->nullable();
            $table->string('customer_phone',15)->nullable();
            $table->string('customer_info',255)->nullable();
            $table->char('uid', 36);
            $table->timestamps();
            
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
