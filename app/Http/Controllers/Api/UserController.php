<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'query' => 'nullable',
            'perPage' => 'nullable'
        ]);

        $perPage = $data['perPage'] ?? null;

        $query = User::query()->withCount('tickets');

        if (isset($data['query'])) {
            $query = $query->search($data['query']);
        }

        $query = $query->orderBy('name');

        if ($perPage) {
            return response()->json($query->paginate($perPage));
        }

        return response()->json($query->get());
    }

    public function store(NewUserRequest $request)
    {
        $data = $request->validated();

        try {
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ];

            $user = User::create($userData);

            return response()->json([
                'message' => 'User created.',
                'user' => $user,
                'success' => true
            ]);
        } catch (Exception $e) {
            Log::error('Error while storing user');
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'message' => 'Error while storing user',
                'success' => false,
            ]);
        }
    }

    public function destroy(User $user)
    {
        try {
            Log::info('Deleting user ' . $user->name . ' ...');

            $user->delete();

            Log::info('... Deleted.');

            return response()->json([
                'message' => 'User deleted.',
                'success' => true
            ]);
        } catch (Exception $e) {
            Log::error('Error while deleting user');
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'message' => 'Error while deleting user',
                'success' => false,
            ]);
        }
    }
}
