<?php

use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notification_types')->delete();
 
        $notification_types = array(
            ['id' => 1, 'description' => 'Welcome'],
            ['id' => 2, 'description' => 'Failed Quickbooks Payment'],
            ['id' => 3, 'description' => 'Failed Paypal Payment'],
            ['id' => 4, 'description' => 'Pending Revokation'],
            ['id' => 5, 'description' => 'Membership Terminated']
        );
 
        // Uncomment the below to run the seeder
        DB::table('notification_types')->insert($notification_types);
    }
}
