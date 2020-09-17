<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('member_tiers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('payment_providers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->string('device_identifier')->nullable();
            $table->timestamps();
        });


        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('rfid')->unique();
            $table->date('expire_date');
            $table->integer('member_status_id')->unsigned();
            $table->integer('member_tier_id')->unsigned();
            $table->integer('payment_provider_id')->unsigned();
            $table->timestamps();

            $table->foreign('member_status_id')->references('id')->on('member_statuses');
            $table->foreign('member_tier_id')->references('id')->on('member_tiers');
            $table->foreign('payment_provider_id')->references('id')->on('payment_providers');
        });


        schema::create('member_resource', function(Blueprint $table){
            $table->increments('id');
            $table->date('expire_date')->nullable();
            $table->integer('member_id')->unsigned();
            $table->integer('resource_id')->unsigned();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('resource_id')->references('id')->on('resources');
        });

        DB::statement('Create View member_table
As
Select m.rfid as hash, m.name, m.email, m.expire_date, r.id as resource_id, r.description as resource
from members m
inner join  member_resource mr
on m.id = mr.member_id
inner join resources r
on mr.resource_id = r.id
where m.member_status_id = 1;' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('Drop View member_table;');

        Schema::dropIfExists('member_resource');
        Schema::dropIfExists('members');
        Schema::dropIfExists('member_statuses');
        Schema::dropIfExists('member_tiers');
        Schema::dropIfExists('payment_providers');

        Schema::dropIfExists('resources');

    }
}
