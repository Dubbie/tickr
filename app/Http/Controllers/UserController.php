<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('User/Index');
    }

    public function show(int $user)
    {
        return Inertia::render('User/Show', [
            'userId' => $user
        ]);
    }
}
