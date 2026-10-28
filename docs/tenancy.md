# Multi-Tenancy Architecture

## Tenancy Isolation Model
Returns & Refund Manager implements a high-security shared-database multi-tenant architecture with strict query-level and application-level isolation boundaries.

### Tenancy Hierarchy
```
Platform Super Admin
  └── Tenant (RetailSphere, TechCare, etc.)
        ├── Legal Entities / Companies
        │     └── Regions
        │           └── Physical Branches & Service Hubs
        │                 └── Warehouses & Quarantine Bins
        ├── Subscription Plan & Quotas
        ├── Enabled Modular Features
        └── Customers, Orders, RMAs, Refunds, & Financial Ledgers
```

### Automatic Scoping via `TenantScope`
All tenant-owned models use the `App\Traits\BelongsToTenant` trait, which automatically registers `App\Scopes\TenantScope`.

When a request executes under an active tenant context:
```sql
SELECT * FROM rmas WHERE rmas.tenant_id = 1;
```

Attempting to access another tenant's records throws an HTTP 404 or authorization failure.

### Testing Tenant Isolation
Automated tests in `tests/Feature/TenancyIsolationTest.php` verify:
1. Tenant A cannot see Tenant B's RMAs or orders.
2. Tenant context switches immediately adjust Eloquent global query scopes.
3. System logs and audit entries preserve immutable `tenant_id` references.
