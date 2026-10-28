<?php

namespace App\Services;

use App\Models\TenantModule;
use Illuminate\Support\Facades\Cache;

class ModuleManager
{
    public function __construct(protected TenantContext $tenantContext) {}

    /**
     * Complete enterprise module catalog containing all 90 capabilities.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getCatalog(): array
    {
        return [
            // Core Operations (1-15)
            'rma_core' => [
                'name' => 'RMA Core Engine',
                'category' => 'Core Operations',
                'description' => 'Complete returns merchandise authorization lifecycle, requests, approvals, and status tracking.',
                'icon' => 'heroicon-o-arrow-path',
                'is_core' => true,
                'default_enabled' => true,
                'dependencies' => [],
            ],
            'return_policy' => [
                'name' => 'Return Policy & Eligibility Engine',
                'category' => 'Core Operations',
                'description' => 'Configurable policy rules, return windows, product categories, and automated eligibility checking.',
                'icon' => 'heroicon-o-shield-check',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'auto_approval' => [
                'name' => 'Automated Rules-Based Approval Engine',
                'category' => 'Core Operations',
                'description' => 'Automatic approval based on order value thresholds, customer VIP score, and defect reasons.',
                'icon' => 'heroicon-o-check-circle',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core', 'return_policy'],
            ],
            'dispute_management' => [
                'name' => 'Disputes, Appeals & Arbitration',
                'category' => 'Core Operations',
                'description' => 'Customer appeal submission, dispute resolution timeline, evidence exchange, and escalation.',
                'icon' => 'heroicon-o-scale',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'customer_messaging' => [
                'name' => 'In-App Returns Messaging & Comments',
                'category' => 'Core Operations',
                'description' => 'Direct two-way customer communication channel linked to specific RMA cases.',
                'icon' => 'heroicon-o-chat-bubble-left-right',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'sla_tracking' => [
                'name' => 'SLA Management & Escalation Engine',
                'category' => 'Core Operations',
                'description' => 'Target resolution deadlines, breach warnings, manager escalation alerts, and SLA reports.',
                'icon' => 'heroicon-o-clock',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'multi_item_split' => [
                'name' => 'Multi-Item Partial Return Splits',
                'category' => 'Core Operations',
                'description' => 'Return individual line items with different reasons and resolution pathways.',
                'icon' => 'heroicon-o-squares-plus',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'attachment_manager' => [
                'name' => 'Receipt & Photo Evidence Attachments',
                'category' => 'Core Operations',
                'description' => 'Secure customer upload of invoice receipts, packaging photos, and video proof.',
                'icon' => 'heroicon-o-paper-clip',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'barcode_intake' => [
                'name' => 'Barcode & QR Code Scanner Intake',
                'category' => 'Core Operations',
                'description' => 'Rapid warehouse intake using camera and 2D barcode handheld gun scanners.',
                'icon' => 'heroicon-o-qr-code',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'drop_off_locator' => [
                'name' => 'Drop-off Location Finder & Scheduling',
                'category' => 'Core Operations',
                'description' => 'Interactive map and schedule selector for customer in-person store drop-offs.',
                'icon' => 'heroicon-o-map-pin',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'guest_returns' => [
                'name' => 'Guest Order Return Lookup Engine',
                'category' => 'Core Operations',
                'description' => 'Secure order verification via email/phone and order code without requiring an account.',
                'icon' => 'heroicon-o-magnifying-glass',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'return_reasons' => [
                'name' => 'Dynamic Hierarchical Return Reasons',
                'category' => 'Core Operations',
                'description' => 'Multi-level nested reason trees with required customer follow-up questionnaire prompts.',
                'icon' => 'heroicon-o-list-bullet',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'status_webhooks' => [
                'name' => 'Real-Time Status Webhook Dispatches',
                'category' => 'Core Operations',
                'description' => 'HMAC-signed webhook dispatch on every RMA state change to external ERPs and storefronts.',
                'icon' => 'heroicon-o-signal',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'bulk_rma_import' => [
                'name' => 'Bulk RMA CSV/Excel Batch Import',
                'category' => 'Core Operations',
                'description' => 'Batch file intake for wholesale returns, marketplace returns, and logistics batching.',
                'icon' => 'heroicon-o-arrow-up-tray',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['rma_core'],
            ],
            'centralized_auditing' => [
                'name' => 'Immutable RMA Event Audit Trail',
                'category' => 'Core Operations',
                'description' => 'Cryptographic event checksumming of all modifications, notes, and agent actions.',
                'icon' => 'heroicon-o-finger-print',
                'is_core' => true,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],

            // Financial Operations (16-30)
            'refunds' => [
                'name' => 'Refund Management & Gateways',
                'category' => 'Financial Operations',
                'description' => 'Payment gateway refunds, partial refunds, store credit, and ledger balance safety.',
                'icon' => 'heroicon-o-banknotes',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'financial_ledger' => [
                'name' => 'Financial Ledger & Credit Notes',
                'category' => 'Financial Operations',
                'description' => 'Double-entry journal posting, credit note generation, and refund reconciliation.',
                'icon' => 'heroicon-o-calculator',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['refunds'],
            ],
            'credit_notes' => [
                'name' => 'Multilingual Credit Notes & Invoicing',
                'category' => 'Financial Operations',
                'description' => 'PDF and HTML tax credit notes with Bengali, Arabic, and English locale support.',
                'icon' => 'heroicon-o-document-text',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['financial_ledger'],
            ],
            'store_credit' => [
                'name' => 'Digital Store Credit Wallet System',
                'category' => 'Financial Operations',
                'description' => 'Virtual wallet balances with bonus incentives for store credit acceptance over cash.',
                'icon' => 'heroicon-o-wallet',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['refunds'],
            ],
            'restocking_fees' => [
                'name' => 'Dynamic Restocking & Handling Fee Deduction',
                'category' => 'Financial Operations',
                'description' => 'Configurable percentage or flat deductions for opened boxes and buyer remorse returns.',
                'icon' => 'heroicon-o-receipt-percent',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['refunds'],
            ],
            'tax_reversals' => [
                'name' => 'Automated Sales Tax & VAT Reversals',
                'category' => 'Financial Operations',
                'description' => 'Proportional tax recalculation for regional VAT, GST, and state sales taxes.',
                'icon' => 'heroicon-o-receipt-refund',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['refunds'],
            ],
            'shipping_fee_refunds' => [
                'name' => 'Original Shipping Fee Refund Rules',
                'category' => 'Financial Operations',
                'description' => 'Policy-driven eligibility for refunding original outward courier shipping charges.',
                'icon' => 'heroicon-o-truck',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['refunds'],
            ],
            'partial_refund_engine' => [
                'name' => 'Item-Level Partial Refund Allocation',
                'category' => 'Financial Operations',
                'description' => 'Sub-item discount amortization and proportional basket recalculation.',
                'icon' => 'heroicon-o-adjustments-horizontal',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['refunds'],
            ],
            'multi_currency_refunds' => [
                'name' => 'Multi-Currency & FX Rate Balancing',
                'category' => 'Financial Operations',
                'description' => 'Foreign exchange variance handling and locked conversion rate refunding.',
                'icon' => 'heroicon-o-currency-dollar',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['refunds'],
            ],
            'payment_gateway_fallback' => [
                'name' => 'Multi-Gateway Automatic Failover',
                'category' => 'Financial Operations',
                'description' => 'Automated switch to secondary payment processors if primary refund API times out.',
                'icon' => 'heroicon-o-arrow-path-rounded-square',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['refunds'],
            ],
            'bank_transfer_payouts' => [
                'name' => 'Direct Bank & bKash/Nagad Payouts',
                'category' => 'Financial Operations',
                'description' => 'Disbursement to domestic bank accounts and mobile financial services (bKash, Nagad, Rocket).',
                'icon' => 'heroicon-o-building-library',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['refunds'],
            ],
            'idempotent_payout_guard' => [
                'name' => 'Double-Payout Safeguard & Lock Engine',
                'category' => 'Financial Operations',
                'description' => 'Distributed Redis locking and unique idempotency token verification.',
                'icon' => 'heroicon-o-lock-closed',
                'is_core' => true,
                'default_enabled' => true,
                'dependencies' => ['refunds'],
            ],
            'accounting_reconciliation' => [
                'name' => 'Payment Gateway Settlement Reconciliation',
                'category' => 'Financial Operations',
                'description' => 'Matching gateway payout settlement statements against issued refund records.',
                'icon' => 'heroicon-o-clipboard-document-check',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['financial_ledger'],
            ],
            'gift_card_refunds' => [
                'name' => 'Original Gift Card & Store Voucher Re-credit',
                'category' => 'Financial Operations',
                'description' => 'Splitting refunds back onto original promotional coupons and stored-value gift cards.',
                'icon' => 'heroicon-o-gift',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['refunds'],
            ],
            'financial_export_erps' => [
                'name' => 'SAP / NetSuite / QuickBooks Financial Exporter',
                'category' => 'Financial Operations',
                'description' => 'Standardized journal exports formatted for enterprise GL and ERP software.',
                'icon' => 'heroicon-o-cloud-arrow-up',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['financial_ledger'],
            ],

            // Inventory and Warehouse (31-45)
            'inspection' => [
                'name' => 'Receiving & Inspection Grading Engine',
                'category' => 'Inventory and Warehouse',
                'description' => 'Multi-step warehouse receiving, condition checklists, photo grading, and seal verification.',
                'icon' => 'heroicon-o-clipboard-document-check',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'disposition' => [
                'name' => 'Smart Disposition & Restock Routing',
                'category' => 'Inventory and Warehouse',
                'description' => 'Dynamic routing to restock, repackage, refurbish, vendor return, liquidation, or scrap.',
                'icon' => 'heroicon-o-arrow-trending-up',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['inspection'],
            ],
            'quarantine_management' => [
                'name' => 'Quarantine Bin & Damaged Goods Hold',
                'category' => 'Inventory and Warehouse',
                'description' => 'Segregated inventory hold bins for hazmat, damaged items, and safety investigations.',
                'icon' => 'heroicon-o-shield-exclamation',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['inspection'],
            ],
            'refurbishment_pipeline' => [
                'name' => 'Refurbishment & Re-packaging Flow',
                'category' => 'Inventory and Warehouse',
                'description' => 'Internal repair and repackaging work orders to convert Grade B returns to Grade A refurbished.',
                'icon' => 'heroicon-o-sparkles',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['disposition'],
            ],
            'liquidation_b2b' => [
                'name' => 'Secondary Marketplace & B2B Liquidation',
                'category' => 'Inventory and Warehouse',
                'description' => 'Grouping unsellable returns into pallet lots for wholesale auction and liquidators.',
                'icon' => 'heroicon-o-archive-box-arrow-down',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['disposition'],
            ],
            'scrap_writeoff' => [
                'name' => 'Scrap, Recycling & Environmental Write-off',
                'category' => 'Inventory and Warehouse',
                'description' => 'Compliant e-waste disposal tracking and certificate of destruction logging.',
                'icon' => 'heroicon-o-trash',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['disposition'],
            ],
            'rts_return_to_supplier' => [
                'name' => 'Vendor Return & Supplier RTV Claims',
                'category' => 'Inventory and Warehouse',
                'description' => 'Outbound vendor RMA claims for manufacturer defects and vendor chargeback credits.',
                'icon' => 'heroicon-o-arrow-up-right',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['disposition'],
            ],
            'warehouse_zones' => [
                'name' => 'Multi-Zone Return Intake & Staging Bins',
                'category' => 'Inventory and Warehouse',
                'description' => 'Granular warehouse bin locations for return staging, processing, and restocking.',
                'icon' => 'heroicon-o-building-office-2',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['inspection'],
            ],
            'cross_dock_returns' => [
                'name' => 'Cross-Docking Return Redistribution',
                'category' => 'Inventory and Warehouse',
                'description' => 'Immediate re-allocation of pristine returned items to pending outgoing orders.',
                'icon' => 'heroicon-o-arrows-pointing-out',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['inspection'],
            ],
            'serial_tracking' => [
                'name' => 'Individual Item Serial Number Traceability',
                'category' => 'Inventory and Warehouse',
                'description' => 'Validating returned device serial numbers against the exact serial shipped.',
                'icon' => 'heroicon-o-tag',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['inspection'],
            ],
            'batch_lot_tracking' => [
                'name' => 'Batch & Lot Expiry Tracking for Returns',
                'category' => 'Inventory and Warehouse',
                'description' => 'Lot number and shelf-life expiration checking for cosmetics, foods, and chemicals.',
                'icon' => 'heroicon-o-calendar',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['inspection'],
            ],
            'inventory_reservation' => [
                'name' => 'Restock Reservation for Pending Orders',
                'category' => 'Inventory and Warehouse',
                'description' => 'Atomic stock reservation unlocking inventory availability across sales channels.',
                'icon' => 'heroicon-o-check-badge',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['disposition'],
            ],
            'multi_warehouse_routing' => [
                'name' => 'Geographic Smart Warehouse Routing',
                'category' => 'Inventory and Warehouse',
                'description' => 'Route returns to the closest facility with space and specialized inspection capabilities.',
                'icon' => 'heroicon-o-globe-alt',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'shrinkage_audit' => [
                'name' => 'Discrepancy & Warehouse Shrinkage Audits',
                'category' => 'Inventory and Warehouse',
                'description' => 'Variance reporting between in-transit return shipments and verified physical intake.',
                'icon' => 'heroicon-o-magnifying-glass-circle',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['inspection'],
            ],
            'rfid_scan_intake' => [
                'name' => 'RFID Bulk Pallet Receiving & Scanning',
                'category' => 'Inventory and Warehouse',
                'description' => 'Portal scanner support for intake of entire return pallets simultaneously.',
                'icon' => 'heroicon-o-signal',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['inspection'],
            ],

            // After-Sales, Repairs, and Warranty (46-55)
            'warranty' => [
                'name' => 'Warranty Claims & Extended Warranties',
                'category' => 'After-Sales, Repairs, and Warranty',
                'description' => 'Serial number tracking, claim verification, warranty expiration schedules, and certificates.',
                'icon' => 'heroicon-o-check-badge',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'repairs' => [
                'name' => 'Repairs & Diagnostic Workshop Center',
                'category' => 'After-Sales, Repairs, and Warranty',
                'description' => 'Repair tickets, diagnostic steps, labor costs, and spare parts inventory consumption.',
                'icon' => 'heroicon-o-wrench-screwdriver',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['warranty'],
            ],
            'spare_parts' => [
                'name' => 'Spare Parts Inventory & BOM Management',
                'category' => 'After-Sales, Repairs, and Warranty',
                'description' => 'Dedicated parts catalog, reorder points, and repair bill of materials tracking.',
                'icon' => 'heroicon-o-cpu-chip',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['repairs'],
            ],
            'exchanges' => [
                'name' => 'Exchanges & Variant Swaps',
                'category' => 'After-Sales, Repairs, and Warranty',
                'description' => 'Item size, color, or variant swaps, price difference calculations, and replacement orders.',
                'icon' => 'heroicon-o-arrows-right-left',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'replacements' => [
                'name' => 'Advance Replacement & Loaner Units',
                'category' => 'After-Sales, Repairs, and Warranty',
                'description' => 'Dispatch replacement units before receiving broken items, with collateral card holds.',
                'icon' => 'heroicon-o-cube',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['warranty'],
            ],
            'technician_dispatch' => [
                'name' => 'Field Service & On-Site Technician Dispatch',
                'category' => 'After-Sales, Repairs, and Warranty',
                'description' => 'Scheduling in-home or on-premise technician repair visits and geo-routing.',
                'icon' => 'heroicon-o-user-group',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['repairs'],
            ],
            'recall_management' => [
                'name' => 'Product Safety Recall & Batch Campaigns',
                'category' => 'After-Sales, Repairs, and Warranty',
                'description' => 'Mass customer recall outreach, affected serial lists, and compliance reporting.',
                'icon' => 'heroicon-o-megaphone',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['rma_core'],
            ],
            'diagnostic_checklists' => [
                'name' => 'Digital Diagnostic Checklists & QA Scores',
                'category' => 'After-Sales, Repairs, and Warranty',
                'description' => 'Standard operating procedures for technician hardware testing and battery diagnostics.',
                'icon' => 'heroicon-o-clipboard-document-list',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['repairs'],
            ],
            'warranty_certificate_gen' => [
                'name' => 'Multilingual Warranty Certificate Generator',
                'category' => 'After-Sales, Repairs, and Warranty',
                'description' => 'Automated PDF warranty deeds and extended guarantee policies.',
                'icon' => 'heroicon-o-document-check',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['warranty'],
            ],
            'repair_labor_costing' => [
                'name' => 'Technician Hourly Billing & Labor Costing',
                'category' => 'After-Sales, Repairs, and Warranty',
                'description' => 'Time tracking per repair step and automated customer billing for out-of-warranty jobs.',
                'icon' => 'heroicon-o-calculator',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['repairs'],
            ],

            // Logistics and Courier (56-65)
            'reverse_logistics' => [
                'name' => 'Reverse Logistics & Shipping Labels',
                'category' => 'Logistics and Courier',
                'description' => 'Courier integration, automated return label generation, tracking events, and drop-off points.',
                'icon' => 'heroicon-o-truck',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'courier_pathao' => [
                'name' => 'Pathao Courier Reverse Logistics Adapter',
                'category' => 'Logistics and Courier',
                'description' => 'Native Pathao Bangladesh merchant API integration for automated pickup booking.',
                'icon' => 'heroicon-o-paper-airplane',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['reverse_logistics'],
            ],
            'courier_redx' => [
                'name' => 'RedX Reverse Courier Integration',
                'category' => 'Logistics and Courier',
                'description' => 'Automated tracking, reverse pickup parcel creation, and webhook status syncing.',
                'icon' => 'heroicon-o-cube-transparent',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['reverse_logistics'],
            ],
            'courier_steadfast' => [
                'name' => 'Steadfast Logistics Delivery & Pickup',
                'category' => 'Logistics and Courier',
                'description' => 'Nationwide coverage for domestic return courier pickups and consignment management.',
                'icon' => 'heroicon-o-archive-box',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['reverse_logistics'],
            ],
            'carrier_fedex_ups' => [
                'name' => 'FedEx / UPS / DHL Global Carrier API',
                'category' => 'Logistics and Courier',
                'description' => 'Worldwide international prepaid return labels and customs invoice documentation.',
                'icon' => 'heroicon-o-globe-americas',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['reverse_logistics'],
            ],
            'pickup_scheduler' => [
                'name' => 'Doorstep Customer Courier Pickup Booking',
                'category' => 'Logistics and Courier',
                'description' => 'Customer self-service scheduling for courier doorstep collection at preferred time slots.',
                'icon' => 'heroicon-o-calendar-days',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['reverse_logistics'],
            ],
            'paperless_qr_label' => [
                'name' => 'Paperless QR Code Drop-off at Courier',
                'category' => 'Logistics and Courier',
                'description' => 'Mobile QR barcode for customers without printers to scan directly at parcel shops.',
                'icon' => 'heroicon-o-device-phone-mobile',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['reverse_logistics'],
            ],
            'freight_returns' => [
                'name' => 'Oversized Heavy Freight Return Logistics',
                'category' => 'Logistics and Courier',
                'description' => 'Palletized LTL/FTL reverse transportation for large appliances and machinery.',
                'icon' => 'heroicon-o-truck',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['reverse_logistics'],
            ],
            'customs_cross_border' => [
                'name' => 'Cross-Border Reverse Customs Documentation',
                'category' => 'Logistics and Courier',
                'description' => 'Automated commercial invoices and duty drawback relief documentation.',
                'icon' => 'heroicon-o-document-duplicate',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['reverse_logistics'],
            ],
            'realtime_tracking_webhooks' => [
                'name' => 'Courier Milestone Real-Time Tracking',
                'category' => 'Logistics and Courier',
                'description' => 'Event ingestion for in-transit, out-for-delivery, and received courier checkpoints.',
                'icon' => 'heroicon-o-bolt',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['reverse_logistics'],
            ],

            // AI and Automation (66-75)
            'ai_intelligence' => [
                'name' => 'AI Intelligence & Predictive Returns',
                'category' => 'AI and Automation',
                'description' => 'Return reason classification, fraud risk scoring, customer query assistance, and defect insights.',
                'icon' => 'heroicon-o-sparkles',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'ai_fraud_scoring' => [
                'name' => 'Predictive Return Fraud & Abuse Scoring',
                'category' => 'AI and Automation',
                'description' => 'Behavioral fraud model analyzing return velocity, customer history, and serial mismatch risk.',
                'icon' => 'heroicon-o-shield-exclamation',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['ai_intelligence'],
            ],
            'ai_customer_assistant' => [
                'name' => 'AI Returns Support Bot & Resolution RAG',
                'category' => 'AI and Automation',
                'description' => 'Multi-turn conversational bot powered by laravel-ai-hub grounded in store policies.',
                'icon' => 'heroicon-o-chat-bubble-bottom-center-text',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['ai_intelligence'],
            ],
            'ai_defect_vision' => [
                'name' => 'AI Image Quality & Defect Detection Assistant',
                'category' => 'AI and Automation',
                'description' => 'Computer vision analysis of customer upload photos for visible damage and packaging tears.',
                'icon' => 'heroicon-o-eye',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['ai_intelligence'],
            ],
            'ai_sentiment_analysis' => [
                'name' => 'Customer Ticket Sentiment & Churn Risk',
                'category' => 'AI and Automation',
                'description' => 'Real-time NLP sentiment evaluation on customer feedback to flag dissatisfied clients.',
                'icon' => 'heroicon-o-heart',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['ai_intelligence'],
            ],
            'ai_catalog_enrichment' => [
                'name' => 'Catalog Metadata Extraction & Enrichment',
                'category' => 'AI and Automation',
                'description' => 'Automated extraction of dimensions, returnable criteria, and warranty duration via AI.',
                'icon' => 'heroicon-o-server-stack',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['ai_intelligence'],
            ],
            'predictive_return_rates' => [
                'name' => 'Predictive SKU-Level Return Rate Forecaster',
                'category' => 'AI and Automation',
                'description' => 'Machine learning projection of expected return volume per product category.',
                'icon' => 'heroicon-o-chart-bar-square',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['ai_intelligence'],
            ],
            'ai_provider_failover' => [
                'name' => 'Multi-Provider AI Gateway & Token Monitor',
                'category' => 'AI and Automation',
                'description' => 'Failover between OpenAI, Anthropic, Gemini, Groq, and Ollama with budget alerts.',
                'icon' => 'heroicon-o-cpu-chip',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['ai_intelligence'],
            ],
            'workflow_automation' => [
                'name' => 'Visual Automation & Trigger Rules',
                'category' => 'AI and Automation',
                'description' => 'Configurable business rules, automated approvals, SLA triggers, and escalation webhooks.',
                'icon' => 'heroicon-o-bolt',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'smart_disposition_ai' => [
                'name' => 'AI Profit-Maximizing Disposition Optimizer',
                'category' => 'AI and Automation',
                'description' => 'Mathematical algorithm recommending the highest net-recovery route for returned items.',
                'icon' => 'heroicon-o-light-bulb',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['ai_intelligence', 'disposition'],
            ],

            // Enterprise and Compliance (76-85)
            'multi_tenancy' => [
                'name' => 'Enterprise SaaS Multi-Tenant Isolation Engine',
                'category' => 'Enterprise and Compliance',
                'description' => 'Strict tenant scoping, custom domains, data partitioning, and quota metering.',
                'icon' => 'heroicon-o-squares-2x2',
                'is_core' => true,
                'default_enabled' => true,
                'dependencies' => [],
            ],
            'multi_branch' => [
                'name' => 'Multi-Branch & Regional Enterprise Hierarchy',
                'category' => 'Enterprise and Compliance',
                'description' => 'Return-at-different-branch, cross-branch inventory transfers, and location-scoped staff.',
                'icon' => 'heroicon-o-building-storefront',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'cross_branch_returns' => [
                'name' => 'Omnichannel Buy-Online-Return-In-Store (BORIS)',
                'category' => 'Enterprise and Compliance',
                'description' => 'Accept online e-commerce returns at any physical branch with immediate store credit or exchange.',
                'icon' => 'heroicon-o-arrows-pointing-in',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['multi_branch'],
            ],
            'rbac_permissions' => [
                'name' => 'Granular Role-Based Access Control',
                'category' => 'Enterprise and Compliance',
                'description' => 'Fine-grained permissions for branch managers, inspectors, finance officers, and agents.',
                'icon' => 'heroicon-o-key',
                'is_core' => true,
                'default_enabled' => true,
                'dependencies' => [],
            ],
            'gdpr_privacy' => [
                'name' => 'GDPR / CCPA Customer Privacy & Data Scrubbing',
                'category' => 'Enterprise and Compliance',
                'description' => 'One-click customer personal data export and anonymization requests.',
                'icon' => 'heroicon-o-shield-check',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'soc2_audit_trail' => [
                'name' => 'Immutable Security & Compliance Event Log',
                'category' => 'Enterprise and Compliance',
                'description' => 'SOC 2 Type II compliant audit logging of IP addresses, user agents, and authorization events.',
                'icon' => 'heroicon-o-document-magnifying-glass',
                'is_core' => true,
                'default_enabled' => true,
                'dependencies' => [],
            ],
            'sso_saml_oauth' => [
                'name' => 'Single Sign-On SAML 2.0 & Okta/Azure OAuth',
                'category' => 'Enterprise and Compliance',
                'description' => 'Enterprise identity provider federated authentication for corporate staff.',
                'icon' => 'heroicon-o-identification',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => [],
            ],
            'customer_portal' => [
                'name' => 'Customer Self-Service Portal',
                'category' => 'Enterprise and Compliance',
                'description' => 'Dedicated customer portal for initiating returns, downloading labels, and live status tracking.',
                'icon' => 'heroicon-o-user-circle',
                'is_core' => false,
                'default_enabled' => true,
                'dependencies' => ['rma_core'],
            ],
            'attendance_workforce' => [
                'name' => 'Workforce & Branch Attendance Integration',
                'category' => 'Enterprise and Compliance',
                'description' => 'Branch employee scheduling, shift attendance integration adapter, and operational workload stats.',
                'icon' => 'heroicon-o-clock',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['multi_branch'],
            ],
            'tenant_billing_metering' => [
                'name' => 'Subscription Billing, Usage Metering & Invoices',
                'category' => 'Enterprise and Compliance',
                'description' => 'Automated monthly plan billing, return volume metering, and overage invoices.',
                'icon' => 'heroicon-o-credit-card',
                'is_core' => true,
                'default_enabled' => true,
                'dependencies' => [],
            ],

            // Industry-Specific Capabilities (86-90)
            'apparel_fashion_sizing' => [
                'name' => 'Apparel Size Exchange & Fit Recommendations',
                'category' => 'Industry-Specific Capabilities',
                'description' => 'Smart sizing charts and instant 1-click replacement size reservation for garments and footwear.',
                'icon' => 'heroicon-o-sparkles',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['exchanges'],
            ],
            'electronics_imei_lock' => [
                'name' => 'Electronics Serial/IMEI Blacklist & Cloud Lock',
                'category' => 'Industry-Specific Capabilities',
                'description' => 'Verification that returned smartphones/tablets have iCloud / Google FRP locks removed.',
                'icon' => 'heroicon-o-device-phone-mobile',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['serial_tracking'],
            ],
            'perishable_grocery_rma' => [
                'name' => 'Perishable Food Expiry & Cold-Chain RMA',
                'category' => 'Industry-Specific Capabilities',
                'description' => 'Immediate refund without return shipment required for expired or thawed perishables.',
                'icon' => 'heroicon-o-exclamation-triangle',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['rma_core'],
            ],
            'furniture_white_glove' => [
                'name' => 'Furniture & White-Glove Two-Person Pickup',
                'category' => 'Industry-Specific Capabilities',
                'description' => 'Specialized courier dispatch for bulky furniture disassembly and heavy freight handling.',
                'icon' => 'heroicon-o-home-modern',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['freight_returns'],
            ],
            'b2b_wholesale_rma' => [
                'name' => 'B2B Wholesale Lot Claims, Tier Discounts & Credits',
                'category' => 'Industry-Specific Capabilities',
                'description' => 'Wholesale contract-based return quotas, volume discount clawbacks, and consolidated monthly credit memos.',
                'icon' => 'heroicon-o-briefcase',
                'is_core' => false,
                'default_enabled' => false,
                'dependencies' => ['rma_core', 'financial_ledger'],
            ],
        ];
    }

    /**
     * Check if a module is enabled for the current or specified tenant.
     */
    public function isEnabled(string $moduleKey, ?int $tenantId = null): bool
    {
        $tenantId = $tenantId ?? $this->tenantContext->getTenantId();
        if (! $tenantId) {
            $catalog = $this->getCatalog();

            return $catalog[$moduleKey]['default_enabled'] ?? false;
        }

        $catalog = $this->getCatalog();
        if (isset($catalog[$moduleKey]) && ! empty($catalog[$moduleKey]['is_core'])) {
            return true;
        }

        $cacheKey = "tenant_{$tenantId}_module_{$moduleKey}";

        return Cache::remember($cacheKey, 3600, function () use ($tenantId, $moduleKey, $catalog) {
            $record = TenantModule::where('tenant_id', $tenantId)
                ->where('module_key', $moduleKey)
                ->first();

            if ($record !== null) {
                return (bool) $record->is_enabled;
            }

            return $catalog[$moduleKey]['default_enabled'] ?? false;
        });
    }

    /**
     * Enable a module for a tenant.
     */
    public function enable(string $moduleKey, ?int $tenantId = null, array $settings = []): bool
    {
        $tenantId = $tenantId ?? $this->tenantContext->getTenantId();
        if (! $tenantId) {
            return false;
        }

        TenantModule::updateOrCreate(
            ['tenant_id' => $tenantId, 'module_key' => $moduleKey],
            ['is_enabled' => true, 'settings' => $settings, 'enabled_at' => now()]
        );

        Cache::forget("tenant_{$tenantId}_module_{$moduleKey}");

        return true;
    }

    /**
     * Disable a module for a tenant safely, preserving all records.
     */
    public function disable(string $moduleKey, ?int $tenantId = null): bool
    {
        $catalog = $this->getCatalog();
        if (! empty($catalog[$moduleKey]['is_core'])) {
            return false; // Cannot disable core module
        }

        $tenantId = $tenantId ?? $this->tenantContext->getTenantId();
        if (! $tenantId) {
            return false;
        }

        TenantModule::updateOrCreate(
            ['tenant_id' => $tenantId, 'module_key' => $moduleKey],
            ['is_enabled' => false]
        );

        Cache::forget("tenant_{$tenantId}_module_{$moduleKey}");

        return true;
    }

    /**
     * Get list of modules by category.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getModulesByCategory(): array
    {
        $categorized = [];
        foreach ($this->getCatalog() as $key => $module) {
            $category = $module['category'];
            if (! isset($categorized[$category])) {
                $categorized[$category] = [];
            }
            $categorized[$category][$key] = $module;
        }

        return $categorized;
    }
}
