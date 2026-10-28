# GDPR, CCPA & Customer Privacy Compliance

## Privacy Capabilities
Returns & Refund Manager adheres to European GDPR and California CCPA privacy standards:

1. **Right to Access & Data Portability**: Customers can request a full machine-readable JSON export of all their personal data, past orders, return requests, and financial receipts.
2. **Right to Erasure (Anonymization)**:
   - When an erasure request is executed via `/admin/privacy`, customer PII (name, email, phone, shipping addresses) is cryptographically scrambled (`DELETED_USER_xxxxx`).
   - Transactional accounting and tax credit records are preserved in anonymized form to comply with mandatory statutory financial retention laws.
3. **Privacy Request Auditing**: All erasure and export requests are recorded in `customer_privacy_requests` with tracking tokens, compliance timestamps, and approving officer IDs.
