<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class JobTitleFactory extends Factory
{
    public static $jobTitles = [
        'Manager',
        'Supervisor',
        'Staff',
        'Senior Staff',
        'Junior Staff',
        'HRD Officer',
        'IT Engineer',
        'System Analyst',
        'Accountant',
        'Business Analyst',
        'Customer Relation',
    ];

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(self::$jobTitles),
        ];
    }
}
