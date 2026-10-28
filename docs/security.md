# Security, IDOR Protection & Cryptographic Auditing

## Security Architecture

### 1. Insecure Direct Object Reference (IDOR) Protection
- All tenant models are bound to `TenantScope`.
- Controller queries utilize Route Model Binding with explicit tenant checks.
- API requests validate tenant ID against authenticated token claims before database query execution.

### 2. Double-Payout & Race Condition Prevention
- Database row locks (`DB::transaction` with `lockForUpdate()`) ensure remaining refundable balances cannot be depleted by concurrent requests.
- Unique `idempotency_key` constraint on the `refunds` table prevents double-issuance of refunds.

### 3. Cryptographic Audit Logging
- `App\Services\AuditLogService` writes tamper-evident audit records into the `audit_logs` table.
- Events log IP address, user agent, actor ID, old values, new values, and a SHA-256 integrity checksum.

### 4. Input Sanitization & CSRF
- CSRF protection enabled across all web and portal routes.
- Strict Form Request validation on all user input.
- File uploads restricted to safe MIME types (PNG, JPG, PDF, WEBP) with randomized storage names.
