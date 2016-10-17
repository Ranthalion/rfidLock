<?php
 
use Illuminate\Database\Seeder;
class ResourceSeeder extends Seeder {
 
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('resources')->delete();
 
        $resources = array(
            ['id' => 1, 'description' => 'Quickbooks', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 2, 'description' => 'Paypal', 'created_at' => new DateTime, 'updated_at' => new DateTime]
        );
 
        // Uncomment the below to run the seeder
        DB::table('resources')->insert($resources);
    }
 
}