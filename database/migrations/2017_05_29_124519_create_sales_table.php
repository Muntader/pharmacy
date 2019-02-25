<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {

            $table->increments('id');
            $table->bigInteger('billing_number');
            $table->integer('total_price');
            $table->string('customer_name',40)->nullable();
            $table->string('customer_phone',15)->nullable();
            $table->json('customer_orders');
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
        Schema::dropIfExists('sales');
    }
}
