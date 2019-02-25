<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {

            $table->increments('id');
            $table->string('ph_name', 40)->nullable();
            $table->string('ph_address', 40)->nullable();
            $table->integer('ph_telephone', 15)->nullable();
            $table->string('ph_email', 40)->nullable();
            $table->integer('ph_fax', 15)->nullable();
            $table->string('ph_language', 3)->nullable();
            $table->string('ph_export_invoice_type', 1)->nullable();
            $table->integer('ph_tax_rate', 2)->nullable();
            $table->string('ph_currency', 8)->nullable();
            $table->string('ph_barcode_type', 15)->nullable();
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
        Schema::dropIfExists('settings');
    }
}
