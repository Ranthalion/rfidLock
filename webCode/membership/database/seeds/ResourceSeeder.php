<?php
 
use Illuminate\Database\Seeder;
class ResourceSeeder extends Seeder {
 
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('resources')->delete();
 
        $resources = array(
            ['id' => 1, 'network_address' => null, 'api_key' => null, 'description' => 'Main Door', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 2, 'network_address' => null, 'api_key' => null, 'description' => 'Tech Lab Door', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 3, 'network_address' => null, 'api_key' => null, 'description' => 'CNC Router', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 4, 'network_address' => null, 'api_key' => null, 'description' => '3020 Engraver', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 5, 'network_address' => null, 'api_key' => null, 'description' => 'Laser Cutter', 'created_at' => new DateTime, 'updated_at' => new DateTime]
        );
 
        // Uncomment the below to run the seeder
        DB::table('resources')->insert($resources);
    }
 
}