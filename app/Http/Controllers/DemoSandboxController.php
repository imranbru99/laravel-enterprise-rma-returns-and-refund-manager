<?php

namespace App\Http\Controllers;

use App\Models\Rma;
use App\Models\User;
use App\Services\InventoryEngineService;
use App\Services\RefundEngineService;
use App\Services\RmaEngineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DemoSandboxController extends Controller
{
    public function __construct(
        protected RmaEngineService $rmaEngine,
        protected InventoryEngineService $inventoryEngine,
        protected RefundEngineService $refundEngine
    ) {}

    /**
     * Interactive Sandbox Demo Page showing real-time step-by-step RMA progression.
     */
    public function index(): View
    {
        $demoRma = Rma::with(['order', 'customer', 'branch', 'items.product', 'inspections', 'refunds'])
            ->latest()
            ->first();

        $demoAccounts = [
            [
                'role' => 'Platform Super Admin',
                'email' => 'superadmin@demo.example',
                'panel' => 'Platform Control Panel (/platform)',
                'badge' => 'Platform',
                'color' => 'purple',
                'panel_url' => '/platform',
            ],
            [
                'role' => 'Tenant Owner (RetailSphere)',
                'email' => 'owner@retailsphere.demo.example',
                'panel' => 'Operations Admin Panel (/admin)',
                'badge' => 'Tenant Admin',
                'color' => 'blue',
                'panel_url' => '/admin',
            ],
            [
                'role' => 'Branch Manager (Dhaka)',
                'email' => 'manager@retailsphere.demo.example',
                'panel' => 'Branch Queue & Approvals (/admin)',
                'badge' => 'Branch Ops',
                'color' => 'cyan',
                'panel_url' => '/admin',
            ],
            [
                'role' => 'Finance Manager',
                'email' => 'finance@retailsphere.demo.example',
                'panel' => 'Finance & Refunds Panel (/finance)',
                'badge' => 'Finance',
                'color' => 'emerald',
                'panel_url' => '/finance',
            ],
            [
                'role' => 'Warehouse Inspector',
                'email' => 'inspector@retailsphere.demo.example',
                'panel' => 'Warehouse Intake & Grading (/admin)',
                'badge' => 'Warehouse',
                'color' => 'amber',
                'panel_url' => '/admin',
            ],
            [
                'role' => 'Customer Demo Account',
                'email' => 'customer@retailsphere.demo.example',
                'panel' => 'Self-Service Customer Portal (/portal)',
                'badge' => 'Customer',
                'color' => 'indigo',
                'panel_url' => '/portal',
            ],
            [
                'role' => 'TechCare Electronics Admin',
                'email' => 'admin@techcare.demo.example',
                'panel' => 'Repairs & Warranty Admin (/admin)',
                'badge' => 'Service Center',
                'color' => 'sky',
                'panel_url' => '/admin',
            ],
            [
                'role' => 'GlobalReturns 3PL Admin',
                'email' => 'admin@globalreturns.demo.example',
                'panel' => '3PL Reverse Logistics Hub (/admin)',
                'badge' => 'Logistics 3PL',
                'color' => 'slate',
                'panel_url' => '/admin',
            ],
        ];

        return view('marketing.demo', compact('demoRma', 'demoAccounts'));
    }

    /**
     * 1-Click Sandbox Fast Login.
     */
    public function quickLogin(Request $request): RedirectResponse
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            Auth::login($user);
            if ($user->isSuperAdmin()) {
                return redirect('/platform');
            }
            if ($user->isFinanceStaff() && $user->role === 'finance_manager') {
                return redirect('/finance');
            }
            if ($user->isCustomer()) {
                return redirect('/portal');
            }

            return redirect('/admin');
        }

        return redirect('/demo')->with('error', 'Demo user not found. Please ensure database seeders have run.');
    }

    /**
     * Interactive Step Progression in the Sandbox Showcase.
     */
    public function stepAdvance(Request $request): JsonResponse
    {
        $step = $request->input('step');
        $rma = Rma::with(['order', 'customer', 'branch', 'items.product'])->latest()->first();

        if (! $rma) {
            return response()->json(['success' => false, 'message' => 'No RMA available. Run seeders first.']);
        }

        switch ($step) {
            case 'submit':
                $this->rmaEngine->transitionStatus($rma, 'pending_review', 'Customer submitted return request');
                break;
            case 'approve':
                $this->rmaEngine->transitionStatus($rma, 'approved', 'Staff approved return authorization');
                break;
            case 'receive':
                $warehouseId = $rma->branch?->warehouses()->first()?->id ?? 1;
                $this->inventoryEngine->receiveItem($rma, $warehouseId);
                break;
            case 'inspect':
                $warehouseId = $rma->branch?->warehouses()->first()?->id ?? 1;
                $this->inventoryEngine->inspectItem($rma, $warehouseId, 'good', ['packaging_intact' => true, 'accessories_present' => true], 'Product is in excellent condition');
                break;
            case 'refund':
                $amount = (float) ($rma->approved_refund_amount > 0 ? $rma->approved_refund_amount : $rma->estimated_refund_amount);
                $this->refundEngine->processRefund($rma, $amount, 'original_payment_method', 'mock_gateway');
                break;
            case 'close':
                $this->rmaEngine->transitionStatus($rma, 'closed', 'RMA closed and resolved');
                break;
        }

        $freshRma = Rma::with(['inspections', 'refunds'])->find($rma->id);

        return response()->json([
            'success' => true,
            'status' => $freshRma->status,
            'message' => "Successfully executed step: {$step}",
            'rma_number' => $freshRma->rma_number,
        ]);
    }
}
