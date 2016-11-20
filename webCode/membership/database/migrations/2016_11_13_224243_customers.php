<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Customers extends Migration
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
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('payment_provider_id')->unsigned();
            $table->date('last_payment_date')->nullable();
            $table->decimal('last_payment_amount', 7, 2)->nullable();
            $table->string('last_payment_status')->nullable();
            $table->date('next_payment_date')->nullable();
            $table->timestamps();

            $table->foreign('payment_provider_id')->references('id')->on('payment_providers');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->decimal('amount', 7,2);
            $table->string('status');
            $table->integer('payment_provider_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('payment_provider_id')->references('id')->on('payment_providers');
        });

        Schema::table('members', function($table){
            $table->integer('customer_id')->unsigned();

            $table->foreign('customer_id')->references('id')->on('customers');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function ($table) {
            $table->dropColumn('customer_id');
        });
        Schema::dropIfExists('payments');
        Schema::dropIfExists('customers');

    }
}
