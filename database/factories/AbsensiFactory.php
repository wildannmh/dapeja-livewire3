<?php
namespace Database\Factories;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsensiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pegawai_id' => Pegawai::inRandomOrder()->first()->id,
            'tanggal' => $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['hadir', 'sakit', 'izin', 'alpha']),
        ];
    }
}