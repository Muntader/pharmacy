<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLastcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lastcs', function (Blueprint $table) {

            $table->increments('id');
            $table->string('customer_id', 20);
            $table->number('customer_invoiceno', 15);
            $table->char('uid', 36);
            $table->timestamps();

            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lastcs');
    }
}
