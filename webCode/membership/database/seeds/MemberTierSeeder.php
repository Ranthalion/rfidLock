<?php
 
use Illuminate\Database\Seeder;
class MemberTierSeeder extends Seeder {
 
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('member_tiers')->delete();
 
        $tiers = array(
            ['id' => 1, 'description' => 'Student', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 2, 'description' => 'Standard', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 3, 'description' => 'Premium', 'created_at' => new DateTime, 'updated_at' => new DateTime]
        );
 
        // Uncomment the below to run the seeder
        DB::table('member_tiers')->insert($tiers);
    }
 
}