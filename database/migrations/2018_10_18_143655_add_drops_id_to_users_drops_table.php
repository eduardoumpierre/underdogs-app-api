<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDropsIdToUsersDropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_drops', function (Blueprint $table) {
            $table->dropColumn(['levels_drops_id']);
            $table->integer('drops_id')->nullable()->unsigned();
            $table->foreign('drops_id')->references('id')->on('levels_drops');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_drops', function (Blueprint $table) {
            $table->dropColumn(['drops_id']);
            $table->integer('levels_drops_id')->unsigned();
            $table->foreign('levels_drops_id')->references('id')->on('levels_drops');
        });
    }
}
