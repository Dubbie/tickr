<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createSuperAdmin();

        User::factory(10)->create();
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
