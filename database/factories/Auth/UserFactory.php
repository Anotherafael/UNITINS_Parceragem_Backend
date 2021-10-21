<?php

namespace Database\Factories\Auth;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'name' => 'Serena Williams',
            'email' => $this->faker->unique()->safeEmail(),
            'document_id' => rand(11111111, 99999999),
            'phone' => rand(11111111, 99999999),
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ];
    }
}
