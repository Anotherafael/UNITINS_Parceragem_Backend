<?php

namespace Database\Factories\Transaction;

use Carbon\Carbon;
use App\Models\Service\Task;
use App\Models\Auth\Professional;
use App\Models\Transaction\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $service = Task::where('name', '=', 'Ansiedade')->first();
        $user = Professional::factory()->create();
        return [
            'id' => $this->faker->uuid,
            'service_id' => $service->id,
            'professional_id' => $user->id,
            'price' => 1000,
            'date' => Carbon::now()
        ];
    }
}
