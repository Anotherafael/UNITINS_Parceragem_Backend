<?php

namespace Database\Factories\Transaction;

use App\Models\Auth\User;
use App\Models\Transaction\Order;
use App\Models\Transaction\RequestOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RequestOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create();
        return [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'status' => 1
        ];
    }
}
