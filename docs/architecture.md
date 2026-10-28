# System Architecture

## Overview
Returns & Refund Manager is built as a high-performance modular monolith using **Laravel 13** and **Filament v5** on **PHP 8.5+**. It features strict tenant isolation, a customizable 90-module capability engine, multi-branch reverse logistics, AI triage, and double-entry financial accounting.

```mermaid
graph TD
    User["End Users / Mobile / Browser"] --> WebRoute["Web Routes / CDN"]
    Customer["Customers"] --> Portal["Customer Portal (/portal)"]
    Admin["Ops Staff"] --> AdminPanel["Filament Admin Panel (/admin)"]
    PlatformAdmin["Super Admins"] --> PlatformPanel["Filament Platform Panel (/platform)"]
    FinanceStaff["Finance Officers"] --> FinancePanel["Filament Finance Panel (/finance)"]
    Storefronts["E-Commerce / ERPs"] --> RestAPI["REST API v1 (/api/v1)"]

    subgraph "Application Layer"
        TenantContext["Tenant & Branch Context"]
        ModuleManager["90-Module Manager"]
        RmaEngine["RMA Lifecycle State Machine"]
        RefundEngine["Refund & Ledger Engine"]
        InventoryEngine["Receiving, Grading & Disposition"]
        WarrantyEngine["Warranty & Repair Center"]
        AiEngine["Laravel AI Hub Multi-Provider"]
    end

    subgraph "Data & Persistence Layer"
        MySQL["Relational DB (MySQL / PostgreSQL / SQLite)"]
        Redis["Redis Cache & Queue Workers"]
        Storage["Object Storage / PDF Documents"]
    end

    WebRoute --> TenantContext
    Portal --> TenantContext
    AdminPanel --> TenantContext
    PlatformPanel --> TenantContext
    FinancePanel --> TenantContext
    RestAPI --> TenantContext

    TenantContext --> ModuleManager
    ModuleManager --> RmaEngine
    RmaEngine --> RefundEngine
    RmaEngine --> InventoryEngine
    RmaEngine --> WarrantyEngine
    RmaEngine --> AiEngine

    RefundEngine --> MySQL
    InventoryEngine --> MySQL
    AiEngine --> Redis
    RmaEngine --> Storage
```

## Modular Monolith Structure
- **Domain Services**: Encapsulated within `app/Services/` (`RmaEngineService`, `RefundEngineService`, `InventoryEngineService`, `WarrantyEngineService`, `AiAssistantService`, `PdfDocumentService`, `AuditLogService`).
- **Scoped Panels**:
  - `AdminPanelProvider` (`/admin`): Daily returns, inspections, approvals, branch inventory, and repairs.
  - `PlatformPanelProvider` (`/platform`): Multi-tenant management, SaaS plan quotas, global audit records.
  - `FinancePanelProvider` (`/finance`): Refund verification, credit note emission, double-entry journals.
- **Tenant Isolation**:
  - `App\Services\TenantContext`: Thread-safe session and request scoped tenant resolver.
  - `App\Scopes\TenantScope`: Automatically injects `tenant_id` WHERE clauses into Eloquent queries.
  - `App\Traits\BelongsToTenant`: Attaches global tenant scope and auto-populates `tenant_id` during creation.
