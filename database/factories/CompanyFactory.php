<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name() . ' GARMENTS PVT LTD',
            'address' => fake()->address(),
            'district' => fake()->city(),
            'state' => fake()->citySuffix(),
            'pincode' => fake()->postcode(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'gst' => fake()->randomKey(),
            'website' => fake()->url(),
            'created_by' => User::pluck('id')->random()
        ];
    }
}
