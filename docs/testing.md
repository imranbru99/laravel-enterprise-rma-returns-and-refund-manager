# Automated Testing Suite

## Test Strategy
The application maintains a comprehensive automated PHPUnit test suite validating business-critical flows, security boundaries, and data integrity:

| Test Suite | File | Focus Areas |
|---|---|---|
| Tenancy Isolation | `tests/Feature/TenancyIsolationTest.php` | Verifies `TenantScope` prevents cross-tenant data leakage and ensures clean context switching. |
| RMA Workflow | `tests/Feature/RmaWorkflowTest.php` | Validates eligibility evaluation, sequential state machine transitions, status history logging, and PDF label rendering. |
| Refund Safety | `tests/Feature/RefundSafetyEngineTest.php` | Asserts remaining refundable balance checks, idempotency duplicate prevention, double-entry journal balancing, and digital store credit wallet. |
| 90-Module Engine | `tests/Feature/ModuleManagementTest.php` | Verifies 90-module catalog integrity, safe non-destructive toggling, and core module protection. |
| Public & Portal | `tests/Feature/PublicAndPortalWorkflowTest.php` | Tests marketing routes, customer portal guest RMA lookup, and interactive demo step advance. |
| REST API v1 | `tests/Feature/RmaApiTest.php` | Validates API RMA listing, RMA submission, and 90-module catalog endpoint. |

## Running the Tests
```bash
# Run full test suite
php artisan test

# Run with compact output
php artisan test --compact

# Filter specific test suite
php artisan test --filter=RmaWorkflowTest
```
*Current Suite Status: 19 Tests, 78 Assertions, 100% Pass Rate.*
