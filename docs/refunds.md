# Refund Engine & Financial Ledger

## Strict Financial Safeguards

The `RefundEngineService` implements enterprise-grade financial rules:

### 1. Refundable Balance Check
Refunds can never exceed `order.paid_amount - order.refunded_amount`. Any attempt to over-refund throws a `ValidationException`.

### 2. Idempotency Lock
Every refund request carries an `idempotency_key` (generated or client-provided). Subsequent execution with the same key blocks duplicate payouts.

### 3. Double-Entry Accounting Journal
Every settled refund automatically generates a balanced `FinancialJournal` and `FinancialJournalLine` entries:
- **Debit**: Returns & Allowances (`4010-RETURNS-EXPENSE`)
- **Credit**: Gateway Clearing / Cash Account (`1010-CASH-GATEWAY`) or Customer Store Credit Liability (`2020-STORE-CREDIT`)

### 4. Multilingual Tax Credit Notes
Compliant credit notes are issued with unique numbering (`CN-YYYYMM-XXXXXX`), balance tracking, and PDF document emission supporting English, Bengali, and Arabic via `imrandevbd/laravel-unicode-pdf`.

### 5. Store Credit Digital Wallet
When customers choose store credit over bank refund, a digital wallet (`StoreCreditAccount`) is credited, generating an audit ledger transaction (`StoreCreditTransaction`).
