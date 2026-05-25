<?php

namespace Database\Factories;

use App\Models\Enfermedad;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnfermedadFactory extends Factory
{
    protected $model = Enfermedad::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->word(),
            'descripcion' => fake()->sentence(),
        ];
    }
}
