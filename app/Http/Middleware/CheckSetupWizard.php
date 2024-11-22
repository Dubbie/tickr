<?php

namespace App\Http\Middleware;

use App\Models\CompanyDetails;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSetupWizard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->routeIs('wizard*')) {
            if (CompanyDetails::count() === 0 && User::count() === 0) {
                return redirect(route('wizard'));
            }
        }

        return $next($request);
    }
}
