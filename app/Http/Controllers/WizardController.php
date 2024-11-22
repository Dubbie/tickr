<?php

namespace App\Http\Controllers;

use App\Http\Requests\FinishSetupWizardRequest;
use App\Services\WizardService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WizardController extends Controller
{
    protected WizardService $wizardService;

    public function __construct(WizardService $wizardService)
    {
        $this->wizardService = $wizardService;
    }

    public function index()
    {
        return Inertia::render('Wizard/Index');
    }

    public function finish(FinishSetupWizardRequest $request)
    {
        $data = $request->validated();

        // Create the company details
        $companyDetails = $this->wizardService->createCompanyDetails($data['companyName']);
        if (!$companyDetails) {
            return redirect()->back()->with([
                'error' => 'Could not create company details'
            ]);
        }

        // Create the user
        $superAdmin = $this->wizardService->createSuperAdmin($data['saName'], $data['saEmail'], $data['saPassword']);
        if (!$companyDetails) {
            return redirect()->back()->with([
                'error' => 'Could not create superadmin user'
            ]);
        }

        // Login the user
        Auth::login($superAdmin);

        // Redirect to dashboard
        return redirect(route('dashboard'));
    }
}
