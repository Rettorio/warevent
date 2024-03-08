<?php

namespace Database\Factories;

use App\Models\Dokumen;
use Illuminate\Database\Eloquent\Factories\Factory;

class DokumenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dokumen::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'judul' => $this->faker->regexify('[A-Za-z]{20}'),
            'tanggalPembuatan' => $this->faker->date(),
            'no_seri' => $this->faker->uuid(),
            'input_dari' => 1,
            'kategori_id' => $this->faker->numberBetween(1, 8)
        ];
    }
}