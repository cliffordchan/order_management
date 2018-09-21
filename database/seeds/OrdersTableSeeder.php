<?php

use Illuminate\Database\Seeder;
use App\Models\Order;

/**
 * Class OrdersTableSeeder
 */
class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Order::class, 100)->create();
    }
}
