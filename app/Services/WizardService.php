<?php

namespace App\Services;

use App\Models\CompanyDetails;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class WizardService
{
    public function createCompanyDetails(string $name)
    {
        try {
            $details = CompanyDetails::create([
                'name' => $name
            ]);

            return $details;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return false;
        }
    }

    public function createSuperAdmin(string $name, string $email, string $password)
    {
        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password)
            ]);

            return $user;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return false;
        }
    }
}
