<?php
 
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class MemberStatusSeeder extends Seeder {
 
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('member_statuses')->delete();
 
        $member_statuses = array(
            ['id' => 1, 'description' => 'Active', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 2, 'description' => 'Inactive', 'created_at' => new DateTime, 'updated_at' => new DateTime]
        );
 
        // Uncomment the below to run the seeder
        DB::table('member_statuses')->insert($member_statuses);
    }
 
}