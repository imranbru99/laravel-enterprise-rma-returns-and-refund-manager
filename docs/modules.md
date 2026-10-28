# 90-Module Capability Engine

## Overview
Returns & Refund Manager contains a catalog of **90 independent modular capabilities** grouped into 8 operational categories. Each business can toggle modules on or off via the administrative UI (`/admin/modules`) without requiring code changes or server restarts.

## Safe Toggling & Data Preservation
- **Non-Destructive**: Disabling a module sets `is_enabled = false` in the database. Historical data (past inspections, warranties, repairs, journals) is preserved.
- **Dependency Guard**: Modules declare explicit prerequisite dependencies. The engine checks dependencies before activating dependent modules.
- **Core Modules**: Crucial foundations (`rma_core`, `multi_tenancy`, `rbac_permissions`, `soc2_audit_trail`, `tenant_billing_metering`) are protected and cannot be disabled.

## Module Categorization (All 90 Modules)

### 1. Core Operations (1–15)
1. `rma_core` — RMA Core Lifecycle Engine
2. `return_policy` — Return Policy & Eligibility Engine
3. `auto_approval` — Automated Rules-Based Approval Engine
4. `dispute_management` — Disputes, Appeals & Arbitration
5. `customer_messaging` — In-App Returns Messaging & Comments
6. `sla_tracking` — SLA Management & Escalation Engine
7. `multi_item_split` — Multi-Item Partial Return Splits
8. `attachment_manager` — Receipt & Photo Evidence Attachments
9. `barcode_intake` — Barcode & QR Code Scanner Intake
10. `drop_off_locator` — Drop-off Location Finder & Scheduling
11. `guest_returns` — Guest Order Return Lookup Engine
12. `return_reasons` — Dynamic Hierarchical Return Reasons
13. `status_webhooks` — Real-Time Status Webhook Dispatches
14. `bulk_rma_import` — Bulk RMA CSV/Excel Batch Import
15. `centralized_auditing` — Immutable RMA Event Audit Trail

### 2. Financial Operations (16–30)
16. `refunds` — Refund Management & Gateways
17. `financial_ledger` — Financial Ledger & Credit Notes
18. `credit_notes` — Multilingual Credit Notes & Invoicing
19. `store_credit` — Digital Store Credit Wallet System
20. `restocking_fees` — Dynamic Restocking & Handling Fee Deduction
21. `tax_reversals` — Automated Sales Tax & VAT Reversals
22. `shipping_fee_refunds` — Original Shipping Fee Refund Rules
23. `partial_refund_engine` — Item-Level Partial Refund Allocation
24. `multi_currency_refunds` — Multi-Currency & FX Rate Balancing
25. `payment_gateway_fallback` — Multi-Gateway Automatic Failover
26. `bank_transfer_payouts` — Direct Bank & bKash/Nagad Payouts
27. `idempotent_payout_guard` — Double-Payout Safeguard & Lock Engine
28. `accounting_reconciliation` — Payment Gateway Settlement Reconciliation
29. `gift_card_refunds` — Original Gift Card & Store Voucher Re-credit
30. `financial_export_erps` — SAP / NetSuite / QuickBooks Financial Exporter

### 3. Inventory and Warehouse (31–45)
31. `inspection` — Receiving & Inspection Grading Engine
32. `disposition` — Smart Disposition & Restock Routing
33. `quarantine_management` — Quarantine Bin & Damaged Goods Hold
34. `refurbishment_pipeline` — Refurbishment & Re-packaging Flow
35. `liquidation_b2b` — Secondary Marketplace & B2B Liquidation
36. `scrap_writeoff` — Scrap, Recycling & Environmental Write-off
37. `rts_return_to_supplier` — Vendor Return & Supplier RTV Claims
38. `warehouse_zones` — Multi-Zone Return Intake & Staging Bins
39. `cross_dock_returns` — Cross-Docking Return Redistribution
40. `serial_tracking` — Individual Item Serial Number Traceability
41. `batch_lot_tracking` — Batch & Lot Expiry Tracking for Returns
42. `inventory_reservation` — Restock Reservation for Pending Orders
43. `multi_warehouse_routing` — Geographic Smart Warehouse Routing
44. `shrinkage_audit` — Discrepancy & Warehouse Shrinkage Audits
45. `rfid_scan_intake` — RFID Bulk Pallet Receiving & Scanning

### 4. After-Sales, Repairs, and Warranty (46–55)
46. `warranty` — Warranty Claims & Extended Warranties
47. `repairs` — Repairs & Diagnostic Workshop Center
48. `spare_parts` — Spare Parts Inventory & BOM Management
49. `exchanges` — Exchanges & Variant Swaps
50. `replacements` — Advance Replacement & Loaner Units
51. `technician_dispatch` — Field Service & On-Site Technician Dispatch
52. `recall_management` — Product Safety Recall & Batch Campaigns
53. `diagnostic_checklists` — Digital Diagnostic Checklists & QA Scores
54. `warranty_certificate_gen` — Multilingual Warranty Certificate Generator
55. `repair_labor_costing` — Technician Hourly Billing & Labor Costing

### 5. Logistics and Courier (56–65)
56. `reverse_logistics` — Reverse Logistics & Shipping Labels
57. `courier_pathao` — Pathao Courier Reverse Logistics Adapter
58. `courier_redx` — RedX Reverse Courier Integration
59. `courier_steadfast` — Steadfast Logistics Delivery & Pickup
60. `carrier_fedex_ups` — FedEx / UPS / DHL Global Carrier API
61. `pickup_scheduler` — Doorstep Customer Courier Pickup Booking
62. `paperless_qr_label` — Paperless QR Code Drop-off at Courier
63. `freight_returns` — Oversized Heavy Freight Return Logistics
64. `customs_cross_border` — Cross-Border Reverse Customs Documentation
65. `realtime_tracking_webhooks` — Courier Milestone Real-Time Tracking

### 6. AI and Automation (66–75)
66. `ai_intelligence` — AI Intelligence & Predictive Returns
67. `ai_fraud_scoring` — Predictive Return Fraud & Abuse Scoring
68. `ai_customer_assistant` — AI Returns Support Bot & Resolution RAG
69. `ai_defect_vision` — AI Image Quality & Defect Detection Assistant
70. `ai_sentiment_analysis` — Customer Ticket Sentiment & Churn Risk
71. `ai_catalog_enrichment` — Catalog Metadata Extraction & Enrichment
72. `predictive_return_rates` — Predictive SKU-Level Return Rate Forecaster
73. `ai_provider_failover` — Multi-Provider AI Gateway & Token Monitor
74. `workflow_automation` — Visual Automation & Trigger Rules
75. `smart_disposition_ai` — AI Profit-Maximizing Disposition Optimizer

### 7. Enterprise and Compliance (76–85)
76. `multi_tenancy` — Enterprise SaaS Multi-Tenant Isolation Engine
77. `multi_branch` — Multi-Branch & Regional Enterprise Hierarchy
78. `cross_branch_returns` — Omnichannel Buy-Online-Return-In-Store (BORIS)
79. `rbac_permissions` — Granular Role-Based Access Control
80. `gdpr_privacy` — GDPR / CCPA Customer Privacy & Data Scrubbing
81. `soc2_audit_trail` — Immutable Security & Compliance Event Log
82. `sso_saml_oauth` — Single Sign-On SAML 2.0 & Okta/Azure OAuth
83. `customer_portal` — Customer Self-Service Portal
84. `attendance_workforce` — Workforce & Branch Attendance Integration
85. `tenant_billing_metering` — Subscription Billing, Usage Metering & Invoices

### 8. Industry-Specific Capabilities (86–90)
86. `apparel_fashion_sizing` — Apparel Size Exchange & Fit Recommendations
87. `electronics_imei_lock` — Electronics Serial/IMEI Blacklist & Cloud Lock
88. `perishable_grocery_rma` — Perishable Food Expiry & Cold-Chain RMA
89. `furniture_white_glove` — Furniture & White-Glove Two-Person Pickup
90. `b2b_wholesale_rma` — B2B Wholesale Lot Claims, Tier Discounts & Credits
