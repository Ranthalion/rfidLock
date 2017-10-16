<?php

use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_types')->delete();
 
        $event_types = array(
            ['id' => 1, 'description' => 'Resource Verification Approved'],
            ['id' => 2, 'description' => 'Resource Verification Declined'],
            ['id' => 3, 'description' => 'Resource Verification Override']
        );
 
        // Uncomment the below to run the seeder
        DB::table('event_types')->insert($event_types);
    }
}
