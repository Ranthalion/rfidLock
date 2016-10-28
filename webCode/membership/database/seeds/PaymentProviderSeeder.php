<?php
 
use Illuminate\Database\Seeder;
class PaymentProviderSeeder extends Seeder {
 
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('payment_providers')->delete();
 
        $providers = array(
            ['id' => 1, 'description' => 'Quickbooks', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 2, 'description' => 'Paypal', 'created_at' => new DateTime, 'updated_at' => new DateTime]
        );
 
        // Uncomment the below to run the seeder
        DB::table('payment_providers')->insert($providers);
    }
 
}