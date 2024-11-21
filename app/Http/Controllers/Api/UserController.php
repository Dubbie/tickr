<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
            $search = '%' . $data['query'] . '%';
            $query = $query->where('name', 'like', $search)->orWhere('email', 'like', $search);
        }

        if ($perPage) {
            return response()->json($query->paginate($perPage));
        }

        return response()->json($query->get());
    }
}
