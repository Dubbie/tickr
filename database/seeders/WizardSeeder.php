<?php

namespace Database\Seeders;

use App\Models\CompanyDetails;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WizardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createCompanyDetails();
        $this->createSuperAdmin();
    }

    private function createCompanyDetails()
    {
        if (!config('company.name')) {
            $this->command->info("No company configured.");
        }

        CompanyDetails::create(['name' => config('company.name')]);
    }

    private function createSuperAdmin()
    {
        if (!config('superadmin.name')) {
            $this->command->info("No superadmin configured.");
        }

        User::create([
            'name' => config('superadmin.name'),
            'email' => config('superadmin.email'),
            'password' => Hash::make(config('superadmin.password'))
        ]);
    }
}
