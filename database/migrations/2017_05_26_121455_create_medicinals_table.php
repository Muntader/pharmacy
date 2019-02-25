<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicinalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicinals', function (Blueprint $table) {

            $table->increments('m_id');
            $table->string('m_gname', 50)->nullable();
            $table->string('m_bname', 50)->nullable();
            $table->text('m_desc', 255)->nullable();
            $table->string('m_country', 30)->nullable();
            $table->string('m_idnumber', 30)->nullable();
            $table->dateTime('m_imdate')->nullable();
            $table->dateTime('m_exdate')->nullable();
            $table->text('m_seffect', 200)->nullable();
            $table->Integer('m_cat')->unsigned();
            $table->integer('m_quantity')->nullable();
            $table->integer('m_price')->nullable();
            $table->string('m_imname', 50)->nullable();
            $table->integer('m_imprice')->nullable();
            $table->integer('m_discount')->nullable();
            $table->string('m_image', 30)->nullable();
            $table->string('m_icon', 10)->nullable();
            $table->string('m_barcodeg', 40)->nullable();
            $table->char('uid', 36);
            $table->timestamps();

            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('m_cat')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicinals');
    }
}
