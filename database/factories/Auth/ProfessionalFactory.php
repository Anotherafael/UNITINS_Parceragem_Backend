<?php

namespace Database\Factories\Auth;

use Illuminate\Support\Str;
use App\Models\Auth\Professional;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfessionalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Professional::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'document_id' => rand(11111111, 99999999),
            'phone' => rand(11111111, 99999999),
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ];
    }
}
