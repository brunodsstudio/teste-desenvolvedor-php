<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produtos>
 */
class ProdutosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nomeProduto' => $this->faker->word(),
            'valor' => $this->faker->numerify('####.##'),
            'CodigoBarras' => $this->faker->ean13(),
        ];
    }
}
