# Webhooks & Event Ingestion

## Outgoing Webhooks
Returns & Refund Manager dispatches real-time webhooks on critical RMA events:
- `rma.created`: Triggered when a new return request is created.
- `rma.status_updated`: Triggered when an RMA advances state machine stages.
- `rma.approved`: Triggered upon manual or automated return approval.
- `rma.item_received`: Triggered upon warehouse barcode intake scan.
- `inspection.completed`: Triggered when condition grading is logged.
- `refund.settled`: Triggered when payment gateway payout settles.

### HMAC Signature Verification
All outgoing webhook payloads contain an `X-RMA-Signature` header calculated using SHA-256 HMAC:
```
hash_hmac('sha256', $payloadJson, $tenantWebhookSecret)
```

## Incoming Webhooks
`POST /api/v1/webhooks/incoming/{provider}`
Supports inbound carrier tracking events from Pathao, RedX, Steadfast, FedEx, and UPS.
Updates shipment status, delivery timestamps, and customer notifications automatically.
