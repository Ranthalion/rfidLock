<?php
 
use Illuminate\Database\Seeder;
class ResourceSeeder extends Seeder {
 
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('resources')->delete();
 
        $resources = array(
            ['id' => 1, 'description' => 'Main Door', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 2, 'description' => 'Tech Lab Door', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 3, 'description' => 'CNC Router', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 4, 'description' => '3020 Engraver', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 5, 'description' => 'Laser Cutter', 'created_at' => new DateTime, 'updated_at' => new DateTime]
        );
 
        // Uncomment the below to run the seeder
        DB::table('resources')->insert($resources);
    }
 
}