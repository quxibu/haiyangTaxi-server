<?php

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Driver;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $driver_ids = Driver::all()->pluck('id')->toArray();


        //已完成订单
        User::all()->each(function (User $user) use ($driver_ids) {

            $count = random_int(5, 10);
            for ($i = 0; $i < $count; $i++)
            {
                factory(Order::class)->create([
                    'status' => Order::ORDER_STATUS_COMPLETED,
                    'user_id' => $user->id,
                    'driver_id' => array_random($driver_ids),
                    'completed_at' => now(),
                ]);
            }

        });


        //已取消订单
        User::all()->each(function (User $user) use ($driver_ids) {

            $count = random_int(5, 10);
            for ($i = 0; $i < $count; $i++)
            {
                factory(Order::class)->create([
                    'status' => Order::ORDER_STATUS_CLOSED,
                    'user_id' => $user->id,
                    'driver_id' => array_random($driver_ids),
                    'closed_at' => now(),

                    'close_from' => array_random(array_keys(Order::$orderCloseFromMap)),
                    'close_reason' => '数据填充...',
                ]);
            }

        });


        //司机订单总数
        Driver::all()->each(function (Driver $driver) {
            $driver->order_count = $driver->orders()->count();
            $driver->save();
        });

    }
}
