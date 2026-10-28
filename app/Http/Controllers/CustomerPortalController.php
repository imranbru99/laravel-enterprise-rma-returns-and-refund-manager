<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Rma;
use App\Models\RmaComment;
use App\Models\RmaDispute;
use App\Services\RmaEngineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerPortalController extends Controller
{
    public function __construct(protected RmaEngineService $rmaEngine) {}

    public function login(): View
    {
        return view('portal.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('portal.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid email or password credentials.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login');
    }

    public function dashboard(): View
    {
        $user = Auth::user();
        $customer = Customer::where('email', $user->email)->first();

        $activeRmas = $customer ? Rma::where('customer_id', $customer->id)
            ->whereNotIn('status', ['closed', 'cancelled'])
            ->with(['order', 'items.product'])
            ->latest()
            ->get() : collect();

        $pastRmas = $customer ? Rma::where('customer_id', $customer->id)
            ->whereIn('status', ['closed', 'cancelled'])
            ->with(['order', 'items.product'])
            ->latest()
            ->take(5)
            ->get() : collect();

        $wallet = $customer?->storeCreditAccount;

        return view('portal.dashboard', compact('customer', 'activeRmas', 'pastRmas', 'wallet'));
    }

    public function orders(): View
    {
        $user = Auth::user();
        $customer = Customer::where('email', $user->email)->first();

        $orders = $customer ? Order::where('customer_id', $customer->id)
            ->with(['items.product', 'rmas'])
            ->latest()
            ->paginate(10) : collect();

        return view('portal.orders', compact('customer', 'orders'));
    }

    public function createReturn(Order $order): View
    {
        $order->load(['items.product', 'items.variant', 'branch']);
        $eligibility = $this->rmaEngine->evaluateEligibility($order, $order->items->map(fn ($i) => ['order_item_id' => $i->id, 'quantity' => $i->quantity])->toArray());

        return view('portal.returns-create', compact('order', 'eligibility'));
    }

    public function storeReturn(Order $order, Request $request): RedirectResponse
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.order_item_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.condition_stated' => 'required|string',
            'return_reason' => 'required|string',
            'resolution_type' => 'required|string',
            'return_method' => 'required|string',
            'customer_notes' => 'nullable|string|max:1000',
        ]);

        $data = [
            'tenant_id' => $order->tenant_id,
            'branch_id' => $order->branch_id,
            'order_id' => $order->id,
            'return_reason' => $request->input('return_reason'),
            'resolution_type' => $request->input('resolution_type'),
            'return_method' => $request->input('return_method'),
            'customer_notes' => $request->input('customer_notes'),
            'priority' => 'normal',
            'items' => $request->input('items'),
        ];

        $rma = $this->rmaEngine->createRma($data);

        return redirect()->route('portal.return.show', $rma->rma_number)
            ->with('success', "Return request #{$rma->rma_number} submitted successfully!");
    }

    public function showReturn(string $rmaNumber): View
    {
        $rma = Rma::where('rma_number', $rmaNumber)
            ->with(['items.product', 'items.variant', 'statusHistories', 'comments.user', 'disputes', 'refunds', 'branch', 'latestShipment'])
            ->firstOrFail();

        return view('portal.returns-show', compact('rma'));
    }

    public function postComment(Rma $rma, Request $request): RedirectResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        RmaComment::create([
            'rma_id' => $rma->id,
            'user_id' => Auth::id(),
            'is_internal' => false,
            'message' => $request->input('message'),
        ]);

        return back()->with('success', 'Your message has been delivered to customer support.');
    }

    public function storeDispute(Rma $rma, Request $request): RedirectResponse
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        RmaDispute::create([
            'rma_id' => $rma->id,
            'customer_id' => $rma->customer_id,
            'reason' => $request->input('reason'),
            'status' => 'open',
        ]);

        return back()->with('success', 'Your dispute appeal has been registered for senior manager review.');
    }

    public function guestLookup(Request $request): View
    {
        $rma = null;
        $order = null;

        if ($request->filled('rma_number') && $request->filled('email')) {
            $rma = Rma::where('rma_number', trim($request->input('rma_number')))
                ->whereHas('customer', fn ($q) => $q->where('email', trim($request->input('email'))))
                ->with(['items.product', 'statusHistories'])
                ->first();
        }

        return view('portal.guest-lookup', compact('rma'));
    }
}
