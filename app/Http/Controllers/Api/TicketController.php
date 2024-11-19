<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'query' => 'nullable'
        ]);

        $query = Ticket::query();

        if (isset($data['query'])) {
            $query = $query->where('subject', 'like', '%' . $data['query'] . '%');
        }

        return $query->orderByDesc('created_at')->get();
    }
}
