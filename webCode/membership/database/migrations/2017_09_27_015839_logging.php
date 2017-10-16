<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Logging extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
        });

        Schema::create('event_log', function(Blueprint $table){
            $table->increments('id');
            $table->string('rfid');
            $table->integer('event_type_id')->unsigned();
            $table->string('data')->nullable();
            $table->timestamps();
            
            $table->foreign('event_type_id')->references('id')->on('event_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_log');
        Schema::dropIfExists('event_types');
    }
}
