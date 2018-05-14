<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLevelsDropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('levels_drops', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('levels_id')->unsigned();
            $table->foreign('levels_id')->references('id')->on('levels');
            $table->integer('drops_id')->unsigned();
            $table->foreign('drops_id')->references('id')->on('drops');
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
        Schema::dropIfExists('levels_drops');
    }
}
