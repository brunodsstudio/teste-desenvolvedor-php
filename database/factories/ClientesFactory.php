<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clientes>
 */
class ClientesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nomeCliente' => $this->faker->name(),
            'CPF' => $this->faker->numerify('###.###.###-##'),
            'Email' => $this->faker->unique()->safeEmail()
        ];
    }
}
