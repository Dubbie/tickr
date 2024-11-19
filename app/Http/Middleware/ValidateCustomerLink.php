<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ValidateCustomerLink
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uniqueLink = $request->route('link');

        $customer = Customer::where('unique_link', $uniqueLink)->first();

        if (!$customer) {
            return abort(403, 'Invalid or unauthorized access.');
        }

        $request->merge([
            'customer' => $customer
        ]);

        Inertia::share('customer', fn(Request $request) => $request->get('customer'));

        return $next($request);
    }
}
