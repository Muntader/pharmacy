<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOauthAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('oauth_access_tokens', 'user_id')) {
           Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->dropColumn('user_id');
           });
            Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->char('user_id',36);
           });
         }else{
                        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->dropColumn('user_id');
           });
           Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->char('user_id',36);
           });
         }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oauth_access_tokens', function (Blueprint $table) {
          
        });
    }
}
