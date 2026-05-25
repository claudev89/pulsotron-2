<?php

namespace Database\Factories;

use App\Models\Sintoma;
use Illuminate\Database\Eloquent\Factories\Factory;

class SintomaFactory extends Factory
{
    protected $model = Sintoma::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->word(),
            'descripcion' => fake()->sentence(),
        ];
    }
}
