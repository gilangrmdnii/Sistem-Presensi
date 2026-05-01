<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BarcodeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'QR Code ' . fake()->unique()->word(),
            'value' => 'MAZ-'.strtoupper(Str::random(10)),
            'radius' => 50,
            // Default near Jakarta (MAZ office area)
            'latitude' => -6.2088 + (mt_rand(-5, 5) / 1000),
            'longitude' => 106.8456 + (mt_rand(-5, 5) / 1000),
        ];
    }
}
