<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use App\Models\CreditNote;
use App\Models\Customer;
use App\Models\Disposition;
use App\Models\FinancialJournal;
use App\Models\FinancialJournalLine;
use App\Models\Inspection;
use App\Models\InventoryStock;
use App\Models\KnowledgeDocument;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Refund;
use App\Models\RepairJob;
use App\Models\ReturnPolicy;
use App\Models\ReturnShipment;
use App\Models\Rma;
use App\Models\RmaComment;
use App\Models\RmaDispute;
use App\Models\RmaItem;
use App\Models\RmaStatusHistory;
use App\Models\SaasPlan;
use App\Models\ShippingCarrier;
use App\Models\SparePart;
use App\Models\StoreCreditAccount;
use App\Models\StoreCreditTransaction;
use App\Models\Tenant;
use App\Models\TenantModule;
use App\Models\TenantSubscription;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarrantyClaim;
use App\Models\Workflow;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // ==========================================
        // 1. GLOBAL SAAS PLANS
        // ==========================================
        $starterPlan = SaasPlan::create([
            'key' => 'starter',
            'name' => 'Starter Boutique',
            'description' => 'Ideal for single-store retailers and growing D2C brands.',
            'price_monthly' => 49.00,
            'price_annual' => 490.00,
            'max_users' => 5,
            'max_branches' => 1,
            'max_monthly_rmas' => 150,
            'has_ai_access' => false,
            'has_custom_domain' => false,
            'has_white_label' => false,
            'features' => ['RMA Core', 'Refunds', 'Store Credit', 'Customer Portal'],
        ]);

        $growthPlan = SaasPlan::create([
            'key' => 'growth',
            'name' => 'Growth Omnichannel',
            'description' => 'For fast-scaling retailers and omnichannel chains managing multiple stores.',
            'price_monthly' => 149.00,
            'price_annual' => 1490.00,
            'max_users' => 20,
            'max_branches' => 5,
            'max_monthly_rmas' => 1000,
            'has_ai_access' => true,
            'has_custom_domain' => true,
            'has_white_label' => false,
            'features' => ['RMA Core', 'Refunds', 'Exchanges', 'Multi-Branch', 'AI Intelligence', 'Carrier Connectors'],
        ]);

        $enterprisePlan = SaasPlan::create([
            'key' => 'enterprise',
            'name' => 'Enterprise Global',
            'description' => 'For multinational retail chains, 3PL reverse logistics hubs, and manufacturers.',
            'price_monthly' => 499.00,
            'price_annual' => 4990.00,
            'max_users' => 100,
            'max_branches' => 50,
            'max_monthly_rmas' => 10000,
            'has_ai_access' => true,
            'has_custom_domain' => true,
            'has_white_label' => true,
            'features' => ['Unlimited Modules', 'White Label', 'Multi-Company', 'Dedicated SLA', 'Audit Logs', 'Double-Entry Ledgers'],
        ]);

        // ==========================================
        // 2. PLATFORM SUPER ADMIN
        // ==========================================
        $superAdmin = User::create([
            'name' => 'Platform Super Administrator',
            'email' => 'superadmin@demo.example',
            'password' => $password,
            'role' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // ==========================================
        // 3. TENANT A — RETAILSPHERE DEMO (Fashion Retailer)
        // ==========================================
        $tenantA = Tenant::create([
            'name' => 'RetailSphere Global Fashion',
            'slug' => 'retailsphere',
            'subdomain' => 'retailsphere',
            'status' => 'active',
            'plan_id' => $growthPlan->id,
            'trial_ends_at' => now()->addDays(30),
            'branding' => [
                'primary_color' => '#4f46e5',
                'secondary_color' => '#10b981',
                'portal_title' => 'RetailSphere Returns & Exchanges Portal',
            ],
            'settings' => [
                'currency' => 'USD',
                'default_return_window' => 30,
            ],
        ]);

        TenantSubscription::create([
            'tenant_id' => $tenantA->id,
            'plan_id' => $growthPlan->id,
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'starts_at' => now()->subMonths(2),
            'ends_at' => now()->addMonth(),
        ]);

        // Enable RetailSphere Modules: RMA, Refunds, Exchanges, Inventory, Multi-Branch, Reverse Logistics
        $retailsphereModules = ['rma_core', 'return_policy', 'refunds', 'financial_ledger', 'exchanges', 'inventory', 'inspection', 'disposition', 'reverse_logistics', 'multi_branch', 'customer_portal', 'ai_intelligence'];
        foreach ($retailsphereModules as $modKey) {
            TenantModule::create([
                'tenant_id' => $tenantA->id,
                'module_key' => $modKey,
                'is_enabled' => true,
                'enabled_at' => now(),
            ]);
        }

        // Company A
        $companyA = Company::create([
            'tenant_id' => $tenantA->id,
            'name' => 'RetailSphere Apparel Holdings Ltd',
            'code' => 'RS-APP',
            'tax_number' => 'US-987654321',
            'currency' => 'USD',
            'timezone' => 'America/New_York',
        ]);

        // 3 Branches: Dhaka, Chattogram, Sylhet
        $branchDhaka = Branch::create([
            'tenant_id' => $tenantA->id,
            'company_id' => $companyA->id,
            'name' => 'RetailSphere — Dhaka Flagship Hub',
            'code' => 'RS-DHK',
            'address' => 'Gulshan Avenue, Block NW-6',
            'city' => 'Dhaka',
            'country' => 'BD',
            'phone' => '+880 1700 000001',
            'email' => 'dhaka@retailsphere.demo.example',
            'is_active' => true,
            'allow_cross_branch_returns' => true,
            'auto_approve_threshold' => 60.00,
        ]);

        $branchChattogram = Branch::create([
            'tenant_id' => $tenantA->id,
            'company_id' => $companyA->id,
            'name' => 'RetailSphere — Chattogram Port Galleria',
            'code' => 'RS-CTG',
            'address' => 'Agrabad Commercial Area',
            'city' => 'Chattogram',
            'country' => 'BD',
            'phone' => '+880 1700 000002',
            'email' => 'chattogram@retailsphere.demo.example',
            'is_active' => true,
            'allow_cross_branch_returns' => true,
            'auto_approve_threshold' => 50.00,
        ]);

        $branchSylhet = Branch::create([
            'tenant_id' => $tenantA->id,
            'company_id' => $companyA->id,
            'name' => 'RetailSphere — Sylhet Zindabazar Center',
            'code' => 'RS-SYL',
            'address' => 'Zindabazar Main Road',
            'city' => 'Sylhet',
            'country' => 'BD',
            'phone' => '+880 1700 000003',
            'email' => 'sylhet@retailsphere.demo.example',
            'is_active' => true,
            'allow_cross_branch_returns' => true,
            'auto_approve_threshold' => 45.00,
        ]);

        // Warehouses for RetailSphere
        $warehouseDhaka = Warehouse::create([
            'tenant_id' => $tenantA->id,
            'branch_id' => $branchDhaka->id,
            'name' => 'Dhaka Central Returns Warehouse',
            'code' => 'WH-DHK-01',
            'type' => 'returns_hub',
            'address' => 'Tejgaon Industrial Area, Dhaka',
            'is_active' => true,
        ]);

        $warehouseChattogram = Warehouse::create([
            'tenant_id' => $tenantA->id,
            'branch_id' => $branchChattogram->id,
            'name' => 'Chattogram Regional Depot',
            'code' => 'WH-CTG-01',
            'type' => 'primary',
            'address' => 'Agrabad, Chattogram',
            'is_active' => true,
        ]);

        // Return Policy for RetailSphere
        $policyA = ReturnPolicy::create([
            'tenant_id' => $tenantA->id,
            'name' => 'RetailSphere Standard 30-Day Apparel Return Policy',
            'version' => 1,
            'return_window_days' => 30,
            'requires_receipt' => true,
            'requires_original_packaging' => true,
            'allow_opened' => true,
            'auto_approve_max_amount' => 60.00,
            'restocking_fee_percent' => 0.00,
            'free_return_shipping' => true,
            'is_active' => true,
        ]);

        // Carriers
        ShippingCarrier::create([
            'tenant_id' => $tenantA->id,
            'code' => 'EXPRESS-RETURNS',
            'name' => 'Express Priority Returns Courier',
            'is_active' => true,
            'api_adapter' => 'mock',
        ]);

        // Users for Tenant A
        $ownerUser = User::create([
            'tenant_id' => $tenantA->id,
            'company_id' => $companyA->id,
            'name' => 'Sarah Jenkins (Tenant Owner)',
            'email' => 'owner@retailsphere.demo.example',
            'password' => $password,
            'role' => 'tenant_owner',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $managerUser = User::create([
            'tenant_id' => $tenantA->id,
            'company_id' => $companyA->id,
            'branch_id' => $branchDhaka->id,
            'name' => 'Tariq Al-Mansoor (Dhaka Branch Manager)',
            'email' => 'manager@retailsphere.demo.example',
            'password' => $password,
            'role' => 'branch_manager',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $agentUser = User::create([
            'tenant_id' => $tenantA->id,
            'company_id' => $companyA->id,
            'branch_id' => $branchDhaka->id,
            'name' => 'Fatima Begum (RMA Agent)',
            'email' => 'agent@retailsphere.demo.example',
            'password' => $password,
            'role' => 'rma_agent',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $financeUser = User::create([
            'tenant_id' => $tenantA->id,
            'company_id' => $companyA->id,
            'name' => 'Michael Chen (Finance Manager)',
            'email' => 'finance@retailsphere.demo.example',
            'password' => $password,
            'role' => 'finance_manager',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $inspectorUser = User::create([
            'tenant_id' => $tenantA->id,
            'company_id' => $companyA->id,
            'branch_id' => $branchDhaka->id,
            'name' => 'Kamal Hossain (Warehouse Inspector)',
            'email' => 'inspector@retailsphere.demo.example',
            'password' => $password,
            'role' => 'warehouse_inspector',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $customerUser = User::create([
            'name' => 'Zubair Rahman (Demo Customer)',
            'email' => 'customer@retailsphere.demo.example',
            'password' => $password,
            'role' => 'customer',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Customer Profile
        $customerA = Customer::create([
            'tenant_id' => $tenantA->id,
            'user_id' => $customerUser->id,
            'customer_code' => 'CUST-RS-001',
            'name' => 'Zubair Rahman',
            'email' => 'customer@retailsphere.demo.example',
            'phone' => '+880 1711 123456',
            'addresses' => [
                'shipping' => 'House 14, Road 5, Dhanmondi, Dhaka',
                'billing' => 'House 14, Road 5, Dhanmondi, Dhaka',
            ],
            'tags' => ['vip', 'regular_shopper'],
            'is_vip' => true,
            'risk_score' => 15,
        ]);

        // Store credit account for customer
        $walletA = StoreCreditAccount::create([
            'tenant_id' => $tenantA->id,
            'customer_id' => $customerA->id,
            'balance' => 45.00,
            'currency' => 'USD',
        ]);

        StoreCreditTransaction::create([
            'account_id' => $walletA->id,
            'type' => 'credit',
            'amount' => 45.00,
            'balance_after' => 45.00,
            'description' => 'Promotional store credit reward',
        ]);

        // Products for RetailSphere
        $prodBlazer = Product::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Executive Slim-Fit Wool Blazer',
            'slug' => 'executive-slim-fit-wool-blazer',
            'sku' => 'BLZ-SLIM-001',
            'category' => 'Apparel',
            'price' => 180.00,
            'cost_price' => 75.00,
            'is_returnable' => true,
            'return_window_days' => 30,
            'warranty_period_months' => 6,
        ]);

        $varBlazerM = ProductVariant::create([
            'tenant_id' => $tenantA->id,
            'product_id' => $prodBlazer->id,
            'sku' => 'BLZ-SLIM-M-NVY',
            'name' => 'Navy Blue / Medium',
            'price' => 180.00,
            'stock_quantity' => 25,
            'attributes' => ['size' => 'M', 'color' => 'Navy Blue'],
        ]);

        $varBlazerL = ProductVariant::create([
            'tenant_id' => $tenantA->id,
            'product_id' => $prodBlazer->id,
            'sku' => 'BLZ-SLIM-L-NVY',
            'name' => 'Navy Blue / Large',
            'price' => 180.00,
            'stock_quantity' => 30,
            'attributes' => ['size' => 'L', 'color' => 'Navy Blue'],
        ]);

        $prodShirt = Product::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Egyptian Cotton Formal Dress Shirt',
            'slug' => 'egyptian-cotton-formal-dress-shirt',
            'sku' => 'SHT-FORMAL-002',
            'category' => 'Apparel',
            'price' => 55.00,
            'cost_price' => 22.00,
            'is_returnable' => true,
            'return_window_days' => 30,
        ]);

        $prodSilkScarf = Product::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Handcrafted Mulberry Silk Scarf',
            'slug' => 'handcrafted-mulberry-silk-scarf',
            'sku' => 'ACC-SCARF-003',
            'category' => 'Accessories',
            'price' => 45.00,
            'cost_price' => 15.00,
            'is_returnable' => true,
            'return_window_days' => 30,
        ]);

        // Inventory stocks in Dhaka Warehouse
        InventoryStock::create([
            'tenant_id' => $tenantA->id,
            'warehouse_id' => $warehouseDhaka->id,
            'product_id' => $prodBlazer->id,
            'product_variant_id' => $varBlazerM->id,
            'quantity_on_hand' => 25,
            'quantity_reserved' => 0,
        ]);

        InventoryStock::create([
            'tenant_id' => $tenantA->id,
            'warehouse_id' => $warehouseDhaka->id,
            'product_id' => $prodBlazer->id,
            'product_variant_id' => $varBlazerL->id,
            'quantity_on_hand' => 30,
            'quantity_reserved' => 0,
        ]);

        // Customer Orders
        // Order 1: Eligible for Auto-Approval return (Under $60)
        $order1 = Order::create([
            'tenant_id' => $tenantA->id,
            'branch_id' => $branchDhaka->id,
            'customer_id' => $customerA->id,
            'order_number' => 'ORD-RS-1001',
            'channel' => 'online',
            'status' => 'delivered',
            'total_amount' => 55.00,
            'paid_amount' => 55.00,
            'refunded_amount' => 0.00,
            'currency' => 'USD',
            'payment_method' => 'credit_card',
            'payment_reference' => 'ch_stripe_mock_1001',
            'ordered_at' => now()->subDays(5),
            'delivered_at' => now()->subDays(3),
        ]);

        $orderItem1 = OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $prodShirt->id,
            'quantity' => 1,
            'unit_price' => 55.00,
            'total_amount' => 55.00,
            'return_status' => 'eligible',
        ]);

        // Order 2: High-Value Order for Manual Review & Cross-Branch Exchange Scenario
        $order2 = Order::create([
            'tenant_id' => $tenantA->id,
            'branch_id' => $branchDhaka->id,
            'customer_id' => $customerA->id,
            'order_number' => 'ORD-RS-1002',
            'channel' => 'in_store',
            'status' => 'delivered',
            'total_amount' => 225.00,
            'paid_amount' => 225.00,
            'refunded_amount' => 0.00,
            'currency' => 'USD',
            'payment_method' => 'credit_card',
            'payment_reference' => 'ch_stripe_mock_1002',
            'ordered_at' => now()->subDays(10),
            'delivered_at' => now()->subDays(8),
        ]);

        $orderItem2_1 = OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $prodBlazer->id,
            'product_variant_id' => $varBlazerM->id,
            'quantity' => 1,
            'unit_price' => 180.00,
            'total_amount' => 180.00,
            'return_status' => 'eligible',
        ]);

        $orderItem2_2 = OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $prodSilkScarf->id,
            'quantity' => 1,
            'unit_price' => 45.00,
            'total_amount' => 45.00,
            'return_status' => 'eligible',
        ]);

        // SCENARIO 1: Eligible Return Automatically Approved (Silk Scarf, $45 <= $60)
        $rma1 = Rma::create([
            'tenant_id' => $tenantA->id,
            'company_id' => $companyA->id,
            'branch_id' => $branchDhaka->id,
            'destination_branch_id' => $branchDhaka->id,
            'customer_id' => $customerA->id,
            'order_id' => $order1->id,
            'return_policy_id' => $policyA->id,
            'rma_number' => 'RMA-202610-00001',
            'status' => 'approved',
            'resolution_type' => 'refund',
            'return_reason' => 'size_fit',
            'customer_notes' => 'Color shade is slightly lighter than photographed.',
            'return_method' => 'mail_in',
            'carrier_code' => 'EXPRESS-RETURNS',
            'tracking_number' => 'TRK-RS-998811',
            'estimated_refund_amount' => 55.00,
            'approved_refund_amount' => 55.00,
            'currency' => 'USD',
            'policy_version' => 1,
            'priority' => 'normal',
            'approved_at' => now()->subDay(),
        ]);

        RmaItem::create([
            'rma_id' => $rma1->id,
            'order_item_id' => $orderItem1->id,
            'product_id' => $prodShirt->id,
            'quantity' => 1,
            'unit_price' => 55.00,
            'refund_amount' => 55.00,
            'return_reason' => 'size_fit',
            'condition_stated' => 'like_new',
            'status' => 'approved',
        ]);

        RmaStatusHistory::create([
            'rma_id' => $rma1->id,
            'from_status' => 'pending_review',
            'to_status' => 'approved',
            'notes' => 'Auto-approved under store threshold ($55.00 <= $60.00)',
        ]);

        ReturnShipment::create([
            'rma_id' => $rma1->id,
            'carrier_code' => 'EXPRESS-RETURNS',
            'tracking_number' => 'TRK-RS-998811',
            'label_barcode' => 'BARCODE-998811',
            'status' => 'label_created',
            'dispatched_at' => now()->subHours(12),
        ]);

        // SCENARIO 2: Ineligible / High-Value Return Sent to Manual Review & Cross-Branch Exchange (Chattogram)
        $rma2 = Rma::create([
            'tenant_id' => $tenantA->id,
            'company_id' => $companyA->id,
            'branch_id' => $branchDhaka->id,
            'destination_branch_id' => $branchChattogram->id, // Customer bought in Dhaka, returning in Chattogram
            'customer_id' => $customerA->id,
            'order_id' => $order2->id,
            'return_policy_id' => $policyA->id,
            'rma_number' => 'RMA-202610-00002',
            'status' => 'pending_review',
            'resolution_type' => 'exchange',
            'return_reason' => 'size_fit',
            'customer_notes' => 'Blazer Medium was too tight around the shoulders. Requesting exchange for Large at Chattogram branch.',
            'return_method' => 'store_dropoff',
            'estimated_refund_amount' => 180.00,
            'approved_refund_amount' => 0.00,
            'currency' => 'USD',
            'policy_version' => 1,
            'priority' => 'high',
            'sla_due_at' => now()->addHours(24),
        ]);

        RmaItem::create([
            'rma_id' => $rma2->id,
            'order_item_id' => $orderItem2_1->id,
            'product_id' => $prodBlazer->id,
            'product_variant_id' => $varBlazerM->id,
            'quantity' => 1,
            'unit_price' => 180.00,
            'refund_amount' => 180.00,
            'return_reason' => 'size_fit',
            'condition_stated' => 'unopened',
            'status' => 'pending',
        ]);

        RmaStatusHistory::create([
            'rma_id' => $rma2->id,
            'from_status' => null,
            'to_status' => 'pending_review',
            'notes' => 'Exceeds auto-approve threshold ($180 > $60). Routed to manual review and cross-branch verification.',
        ]);

        // SCENARIO 3: Fully Settled Refund via Mock Gateway with General Ledger Reconciliation
        $order3 = Order::create([
            'tenant_id' => $tenantA->id,
            'branch_id' => $branchDhaka->id,
            'customer_id' => $customerA->id,
            'order_number' => 'ORD-RS-1003',
            'channel' => 'online',
            'status' => 'delivered',
            'total_amount' => 45.00,
            'paid_amount' => 45.00,
            'refunded_amount' => 45.00,
            'currency' => 'USD',
            'ordered_at' => now()->subDays(14),
            'delivered_at' => now()->subDays(12),
        ]);

        $orderItem3 = OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $prodSilkScarf->id,
            'quantity' => 1,
            'unit_price' => 45.00,
            'total_amount' => 45.00,
            'refunded_amount' => 45.00,
            'return_status' => 'returned',
        ]);

        $rma3 = Rma::create([
            'tenant_id' => $tenantA->id,
            'company_id' => $companyA->id,
            'branch_id' => $branchDhaka->id,
            'customer_id' => $customerA->id,
            'order_id' => $order3->id,
            'rma_number' => 'RMA-202610-00003',
            'status' => 'refunded',
            'resolution_type' => 'refund',
            'return_reason' => 'customer_remorse',
            'return_method' => 'mail_in',
            'estimated_refund_amount' => 45.00,
            'approved_refund_amount' => 45.00,
            'currency' => 'USD',
            'policy_version' => 1,
            'approved_at' => now()->subDays(6),
            'received_at' => now()->subDays(4),
            'closed_at' => now()->subDays(2),
        ]);

        RmaItem::create([
            'rma_id' => $rma3->id,
            'order_item_id' => $orderItem3->id,
            'product_id' => $prodSilkScarf->id,
            'quantity' => 1,
            'unit_price' => 45.00,
            'refund_amount' => 45.00,
            'return_reason' => 'customer_remorse',
            'condition_stated' => 'like_new',
            'status' => 'settled',
        ]);

        $inspection3 = Inspection::create([
            'rma_id' => $rma3->id,
            'warehouse_id' => $warehouseDhaka->id,
            'inspector_id' => $inspectorUser->id,
            'overall_grade' => 'like_new',
            'checklist_results' => ['original_tags_intact' => true, 'unworn' => true],
            'notes' => 'Item verified in perfect original condition.',
            'passed_inspection' => true,
            'suggested_disposition' => 'restock',
            'estimated_restocking_value' => 45.00,
            'inspected_at' => now()->subDays(3),
        ]);

        Disposition::create([
            'rma_id' => $rma3->id,
            'rma_item_id' => $rma3->items()->first()->id,
            'destination_type' => 'restock',
            'destination_warehouse_id' => $warehouseDhaka->id,
            'quantity' => 1,
            'restocked_amount' => 45.00,
            'completed_by' => $inspectorUser->id,
            'completed_at' => now()->subDays(3),
            'notes' => 'Returned item added back to active shelf inventory.',
        ]);

        $refund3 = Refund::create([
            'tenant_id' => $tenantA->id,
            'rma_id' => $rma3->id,
            'order_id' => $order3->id,
            'refund_number' => 'REF-202610-00003',
            'amount' => 45.00,
            'currency' => 'USD',
            'method' => 'original_payment_method',
            'gateway_name' => 'mock_gateway',
            'gateway_transaction_id' => 'TXN_MOCK_45_SETTLED',
            'status' => 'settled',
            'idempotency_key' => 'ref_rs_demo_003',
            'approved_by' => $financeUser->id,
            'approved_at' => now()->subDays(2),
            'processed_at' => now()->subDays(2),
        ]);

        CreditNote::create([
            'tenant_id' => $tenantA->id,
            'rma_id' => $rma3->id,
            'refund_id' => $refund3->id,
            'customer_id' => $customerA->id,
            'credit_note_number' => 'CN-202610-00003',
            'amount' => 45.00,
            'tax_amount' => 4.50,
            'balance_remaining' => 45.00,
            'status' => 'active',
            'issued_at' => now()->subDays(2),
        ]);

        $journal3 = FinancialJournal::create([
            'tenant_id' => $tenantA->id,
            'reference_type' => 'refund',
            'reference_id' => $refund3->id,
            'entry_date' => now()->subDays(2)->toDateString(),
            'description' => "Settled refund for RMA #{$rma3->rma_number} (Order #{$order3->order_number})",
            'total_debit' => 45.00,
            'total_credit' => 45.00,
        ]);

        FinancialJournalLine::create([
            'journal_id' => $journal3->id,
            'account_code' => '4010-RETURNS-EXPENSE',
            'account_name' => 'Returns & Allowances Expense',
            'debit' => 45.00,
            'credit' => 0.00,
            'branch_id' => $branchDhaka->id,
        ]);

        FinancialJournalLine::create([
            'journal_id' => $journal3->id,
            'account_code' => '1010-CASH-GATEWAY',
            'account_name' => 'Payment Gateway Clearing Account',
            'debit' => 0.00,
            'credit' => 45.00,
            'branch_id' => $branchDhaka->id,
        ]);

        // SCENARIO 4: Disputed Return with Inspection Grade Failure
        $rma4 = Rma::create([
            'tenant_id' => $tenantA->id,
            'company_id' => $companyA->id,
            'branch_id' => $branchDhaka->id,
            'customer_id' => $customerA->id,
            'order_id' => $order2->id,
            'rma_number' => 'RMA-202610-00004',
            'status' => 'inspecting',
            'resolution_type' => 'refund',
            'return_reason' => 'defective',
            'customer_notes' => 'Silk scarf hem unraveled upon opening.',
            'return_method' => 'mail_in',
            'estimated_refund_amount' => 45.00,
            'currency' => 'USD',
            'priority' => 'normal',
            'received_at' => now()->subHours(8),
        ]);

        RmaDispute::create([
            'rma_id' => $rma4->id,
            'customer_id' => $customerA->id,
            'reason' => 'Initial warehouse inspection claimed customer tear, but defect existed out of package.',
            'status' => 'under_review',
        ]);

        RmaComment::create([
            'rma_id' => $rma4->id,
            'user_id' => $customerUser->id,
            'is_internal' => false,
            'message' => 'Please re-inspect the hem under microscope. It was unstitched when delivered.',
        ]);

        RmaComment::create([
            'rma_id' => $rma4->id,
            'user_id' => $agentUser->id,
            'is_internal' => false,
            'message' => 'We have received your appeal. Senior quality manager will review photos today.',
        ]);

        // ==========================================
        // 4. TENANT B — TECHCARE DEMO (Electronics & Warranty Repairs)
        // ==========================================
        $tenantB = Tenant::create([
            'name' => 'TechCare Electronics & Service Centers',
            'slug' => 'techcare',
            'subdomain' => 'techcare',
            'status' => 'active',
            'plan_id' => $enterprisePlan->id,
            'branding' => [
                'primary_color' => '#0284c7',
                'portal_title' => 'TechCare Warranty & Repair Center',
            ],
        ]);

        // Enable TechCare Modules: Warranty, Repairs, Spare Parts, Diagnostics, AI
        $techcareModules = ['rma_core', 'warranty', 'repairs', 'inventory', 'inspection', 'disposition', 'ai_intelligence', 'customer_portal'];
        foreach ($techcareModules as $modKey) {
            TenantModule::create([
                'tenant_id' => $tenantB->id,
                'module_key' => $modKey,
                'is_enabled' => true,
                'enabled_at' => now(),
            ]);
        }

        $companyB = Company::create([
            'tenant_id' => $tenantB->id,
            'name' => 'TechCare Devices Inc',
            'code' => 'TECH-DEV',
            'currency' => 'USD',
            'timezone' => 'America/Chicago',
        ]);

        $branchShowroom = Branch::create([
            'tenant_id' => $tenantB->id,
            'company_id' => $companyB->id,
            'name' => 'TechCare Main Showroom & Store',
            'code' => 'TECH-SHOWROOM',
            'country' => 'US',
            'is_active' => true,
        ]);

        $branchService = Branch::create([
            'tenant_id' => $tenantB->id,
            'company_id' => $companyB->id,
            'name' => 'TechCare Authorized Repair Facility',
            'code' => 'TECH-SERVICE',
            'country' => 'US',
            'is_active' => true,
        ]);

        User::create([
            'tenant_id' => $tenantB->id,
            'company_id' => $companyB->id,
            'name' => 'TechCare Admin',
            'email' => 'admin@techcare.demo.example',
            'password' => $password,
            'role' => 'tenant_owner',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $prodTablet = Product::create([
            'tenant_id' => $tenantB->id,
            'name' => 'ProTab 12X Enterprise Tablet 256GB',
            'slug' => 'protab-12x-enterprise-tablet-256gb',
            'sku' => 'TAB-12X-PRO',
            'category' => 'Electronics',
            'price' => 799.00,
            'cost_price' => 450.00,
            'is_returnable' => true,
            'warranty_period_months' => 24,
            'serial_tracking_required' => true,
        ]);

        $partScreen = SparePart::create([
            'tenant_id' => $tenantB->id,
            'name' => '12.4 inch OLED Display Assembly',
            'part_number' => 'PART-DISP-12X',
            'cost_price' => 120.00,
            'sale_price' => 210.00,
            'stock_quantity' => 15,
        ]);

        // SCENARIO 5: Active Warranty Repair Claim
        $customerB = Customer::create([
            'tenant_id' => $tenantB->id,
            'customer_code' => 'CUST-TECH-001',
            'name' => 'Dr. Robert Vance',
            'email' => 'robert.vance@example.com',
            'phone' => '+1 555 432 1098',
        ]);

        $warrantyClaim = WarrantyClaim::create([
            'tenant_id' => $tenantB->id,
            'customer_id' => $customerB->id,
            'product_id' => $prodTablet->id,
            'claim_number' => 'WAR-202610-00010',
            'serial_number' => 'SN-TAB12X-99882211',
            'purchase_date' => now()->subMonths(6)->toDateString(),
            'warranty_expires_at' => now()->addMonths(18)->toDateString(),
            'issue_description' => 'Display glass cracked due to battery thermal swelling. Touch digitizer unresponsive.',
            'status' => 'repair_in_progress',
        ]);

        RepairJob::create([
            'tenant_id' => $tenantB->id,
            'branch_id' => $branchService->id,
            'warranty_claim_id' => $warrantyClaim->id,
            'job_number' => 'REP-202610-00010',
            'diagnosis' => 'Confirmed battery expansion caused screen pressure. Display assembly and Li-ion pack replacement needed.',
            'status' => 'in_progress',
            'labor_cost' => 65.00,
            'parts_cost' => 120.00,
        ]);

        // ==========================================
        // 5. TENANT C — GLOBALRETURNS 3PL DEMO (Reverse Logistics Provider)
        // ==========================================
        $tenantC = Tenant::create([
            'name' => 'GlobalReturns 3PL Reverse Logistics',
            'slug' => 'globalreturns',
            'subdomain' => 'globalreturns',
            'status' => 'active',
            'plan_id' => $enterprisePlan->id,
            'branding' => [
                'primary_color' => '#0f172a',
                'portal_title' => 'GlobalReturns Enterprise Reverse Logistics Hub',
            ],
        ]);

        $companyC = Company::create([
            'tenant_id' => $tenantC->id,
            'name' => 'GlobalReturns Logistics International',
            'code' => 'GRL-3PL',
            'currency' => 'USD',
        ]);

        Branch::create([
            'tenant_id' => $tenantC->id,
            'company_id' => $companyC->id,
            'name' => 'GlobalReturns Central Mega Hub',
            'code' => 'GRL-CENTRAL',
            'country' => 'US',
            'is_active' => true,
        ]);

        User::create([
            'tenant_id' => $tenantC->id,
            'company_id' => $companyC->id,
            'name' => 'Logistics 3PL Admin',
            'email' => 'admin@globalreturns.demo.example',
            'password' => $password,
            'role' => 'tenant_owner',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // ==========================================
        // 6. KNOWLEDGE BASE ARTICLES (FOR RAG AI)
        // ==========================================
        KnowledgeDocument::create([
            'tenant_id' => $tenantA->id,
            'title' => 'RetailSphere Standard Return & Exchange Terms',
            'slug' => 'standard-return-policy',
            'category' => 'policy',
            'content' => 'Customers may return or exchange apparel within 30 days of delivery. Items must be unworn with original tags attached. Returns under $60 with eligible receipt are approved immediately. Store credit is credited with a 5% bonus.',
            'is_published' => true,
        ]);

        KnowledgeDocument::create([
            'tenant_id' => $tenantA->id,
            'title' => 'Cross-Branch Exchange Procedures',
            'slug' => 'cross-branch-exchanges',
            'category' => 'operations',
            'content' => 'Items purchased online or at any RetailSphere store branch (Dhaka, Chattogram, Sylhet) can be returned or exchanged at any other branch. Replacement stock is reserved instantly in the local branch warehouse.',
            'is_published' => true,
        ]);

        // ==========================================
        // 7. VISUAL AUTOMATION TRIGGERS
        // ==========================================
        Workflow::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Auto-Approve Low-Risk Returns Under $60',
            'trigger_event' => 'rma_created',
            'conditions' => [
                ['field' => 'estimated_refund_amount', 'operator' => '<=', 'value' => 60.00],
                ['field' => 'risk_score', 'operator' => '<', 'value' => 30],
            ],
            'actions' => [
                ['action' => 'auto_approve', 'params' => ['notify_customer' => true]],
                ['action' => 'generate_label', 'params' => ['carrier' => 'EXPRESS-RETURNS']],
            ],
            'is_active' => true,
            'execution_count' => 14,
        ]);
    }
}
