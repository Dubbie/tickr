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
        $isSetupComplete = CompanyDetails::exists() && User::exists();

        // Redirect to setup wizard if setup is incomplete and not accessing wizard routes
        if (!$isSetupComplete && !$request->routeIs('wizard*')) {
            return redirect()->route('wizard');
        }

        // Prevent access to the wizard if setup is already complete
        if ($isSetupComplete && $request->routeIs('wizard*')) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
