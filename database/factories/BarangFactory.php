<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Barang::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'keterangan' => $this->faker->regexify('[A-Za-z0-9]{55}'),
            'gambar' => "http://localhost:8000/storage/barang-assets/dummy-sample.jpg",
            'kategori_id' => $this->faker->numberBetween(1, 5),
            // 'padaBagian' => $this->faker->numberBetween(1, 7),
            'padaPegawai' => $this->faker->numberBetween(1, 8),
            // 'padaPegawai' => null,
            'inputDari' => 1,
        ];
    }
}
