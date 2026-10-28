<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use App\Models\ReturnPolicy;
use App\Models\SaasPlan;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\ModuleManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicWebsiteController extends Controller
{
    public function home(): View
    {
        $plans = SaasPlan::where('is_active', true)->get();

        return view('marketing.home', compact('plans'));
    }

    public function features(ModuleManager $moduleManager): View
    {
        $catalog = $moduleManager->getCatalog();

        return view('marketing.features', compact('catalog'));
    }

    public function workflow(): View
    {
        return view('marketing.workflow');
    }

    public function industries(): View
    {
        return view('marketing.industries');
    }

    public function pricing(): View
    {
        $plans = SaasPlan::where('is_active', true)->get();

        return view('marketing.pricing', compact('plans'));
    }

    public function docs(): View
    {
        return view('marketing.docs');
    }

    public function contact(): View
    {
        return view('marketing.contact');
    }

    public function register(): View
    {
        $plans = SaasPlan::where('is_active', true)->get();

        return view('marketing.register', compact('plans'));
    }

    public function storeRegistration(Request $request): RedirectResponse
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'plan_key' => 'nullable|string',
        ]);

        $user = DB::transaction(function () use ($request) {
            $companyName = $request->input('company_name');
            $slug = Str::slug($companyName).'-'.Str::lower(Str::random(4));

            $plan = SaasPlan::where('key', $request->input('plan_key', 'growth'))->first();

            // 1. Create Tenant
            $tenant = Tenant::create([
                'name' => $companyName,
                'slug' => $slug,
                'subdomain' => $slug,
                'status' => 'active',
                'plan_id' => $plan?->id,
                'trial_ends_at' => now()->addDays(14),
                'branding' => [
                    'primary_color' => '#4f46e5',
                    'portal_title' => "{$companyName} Returns Portal",
                ],
            ]);

            // 2. Create Company
            $company = Company::create([
                'tenant_id' => $tenant->id,
                'name' => $companyName,
                'code' => strtoupper(substr($companyName, 0, 4)),
                'currency' => 'USD',
                'timezone' => 'UTC',
            ]);

            // 3. Create Main Branch
            $branch = Branch::create([
                'tenant_id' => $tenant->id,
                'company_id' => $company->id,
                'name' => "{$companyName} Main Headquarters",
                'code' => 'HQ-01',
                'country' => 'US',
                'is_active' => true,
                'allow_cross_branch_returns' => true,
                'auto_approve_threshold' => 50.00,
            ]);

            // 4. Create Warehouse
            Warehouse::create([
                'tenant_id' => $tenant->id,
                'branch_id' => $branch->id,
                'name' => "{$companyName} Central Warehouse",
                'code' => 'WH-01',
                'type' => 'returns_hub',
                'is_active' => true,
            ]);

            // 5. Create Default Return Policy
            ReturnPolicy::create([
                'tenant_id' => $tenant->id,
                'name' => 'Standard 30-Day Return Policy',
                'return_window_days' => 30,
                'requires_receipt' => true,
                'requires_original_packaging' => true,
                'allow_opened' => true,
                'auto_approve_max_amount' => 50.00,
                'is_active' => true,
            ]);

            // 6. Create Owner User
            $user = User::create([
                'tenant_id' => $tenant->id,
                'company_id' => $company->id,
                'branch_id' => $branch->id,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role' => 'tenant_owner',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            return $user;
        });

        Auth::login($user);

        return redirect('/admin')->with('success', 'Welcome to Returns & Refund Manager! Your organization and branches have been set up.');
    }
}
