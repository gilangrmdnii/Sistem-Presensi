<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DivisionFactory extends Factory
{
    public static $divisions = [
        'Human Resources',
        'Teknologi Informasi',
        'Operasional Messaging',
        'Keuangan',
        'Business Development',
        'Customer Support',
    ];

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(self::$divisions),
        ];
    }
}
