<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::firstOrCreate(
            ['email' => 'admin@essa.com'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => 'password'
            ]
        );

        $companies = Company::factory(2)->create();

        foreach ($companies as $company) {
            CompanyUser::create([
                'user_id' => $user->id,
                'company_id' => $company->id
            ]);
        }
    }
}
