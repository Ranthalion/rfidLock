<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    		$this->call(MemberStatusSeeder::class);
    		$this->call(MemberTierSeeder::class);
    		$this->call(PaymentProviderSeeder::class);
    		$this->call(ResourceSeeder::class);
        $this->call(NotificationSeeder::class);
        $this->call(EventTypeSeeder::class);

		 DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
