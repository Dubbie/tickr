<?php

namespace Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create super admin
        if (!config('superadmin.name')) {
            throw new Exception("No superadmin configured.");
        }

        User::create([
            'name' => config('superadmin.name'),
            'email' => config('superadmin.email'),
            'password' => Hash::make(config('superadmin.password'))
        ]);

        $this->call([CustomerSeeder::class]);
    }
}
