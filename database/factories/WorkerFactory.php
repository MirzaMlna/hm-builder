<?php

namespace Database\Factories;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkerFactory extends Factory
{
    protected $model = Worker::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->bothify('TKG###'),
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'birth_date' => $this->faker->date(),
            'address' => $this->faker->address(),
            'photo' => null,
            'daily_salary' => $this->faker->randomFloat(2, 100000, 200000),
            'is_active' => $this->faker->boolean(80),
            'note' => $this->faker->sentence(),
        ];
    }
}
