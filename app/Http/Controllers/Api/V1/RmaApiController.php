<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Rma;
use App\Services\ModuleManager;
use App\Services\RefundEngineService;
use App\Services\RmaEngineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RmaApiController extends Controller
{
    public function __construct(
        protected RmaEngineService $rmaEngine,
        protected RefundEngineService $refundEngine,
        protected ModuleManager $moduleManager
    ) {}

    /**
     * List RMAs.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Rma::with(['order', 'customer', 'branch', 'items.product']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        $rmas = $query->latest()->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $rmas,
        ]);
    }

    /**
     * Create RMA via API.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'return_reason' => 'required|string',
            'resolution_type' => 'required|string',
            'return_method' => 'nullable|string',
            'customer_notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.order_item_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.condition_stated' => 'nullable|string',
        ]);

        $rma = $this->rmaEngine->createRma($validated);

        return response()->json([
            'success' => true,
            'message' => 'RMA authorization created successfully.',
            'data' => $rma->load('items'),
        ], 201);
    }

    /**
     * Show single RMA.
     */
    public function show(string $rmaNumber): JsonResponse
    {
        $rma = Rma::where('rma_number', $rmaNumber)
            ->with(['order', 'customer', 'branch', 'items.product', 'statusHistories', 'inspections', 'refunds'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $rma,
        ]);
    }

    /**
     * Process refund on an RMA.
     */
    public function processRefund(string $rmaNumber, Request $request): JsonResponse
    {
        $rma = Rma::where('rma_number', $rmaNumber)->firstOrFail();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'nullable|string',
            'gateway_name' => 'nullable|string',
            'idempotency_key' => 'nullable|string',
        ]);

        $refund = $this->refundEngine->processRefund(
            rma: $rma,
            amount: (float) $validated['amount'],
            method: $validated['method'] ?? 'original_payment_method',
            gatewayName: $validated['gateway_name'] ?? 'mock_gateway',
            idempotencyKey: $validated['idempotency_key'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Refund processed and general ledger updated.',
            'data' => $refund,
        ]);
    }

    /**
     * List active modules.
     */
    public function modules(): JsonResponse
    {
        $catalog = $this->moduleManager->getCatalog();
        $active = [];

        foreach ($catalog as $key => $mod) {
            $active[$key] = [
                'name' => $mod['name'],
                'category' => $mod['category'],
                'is_enabled' => $this->moduleManager->isEnabled($key),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $active,
        ]);
    }

    /**
     * Incoming simulated webhook endpoint.
     */
    public function incomingWebhook(string $provider, Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'provider' => $provider,
            'status' => 'received_and_verified',
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
