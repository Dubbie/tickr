<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    const LIMIT = 5;

    public function search(Request $request)
    {
        $query = $request->input('search');

        $users = User::search($query)->limit(self::LIMIT)->get();
        $tickets = Ticket::search($query)->limit(self::LIMIT)->get();
        $customers = Customer::search($query)->limit(self::LIMIT)->get();

        return response()->json([
            'users' => $users,
            'tickets' => $tickets,
            'customers' => $customers
        ]);
    }
}
