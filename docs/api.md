# REST API Documentation (v1)

## Overview
All REST APIs are versioned under `/api/v1` and return standardized JSON responses.

### Authentication
API endpoints use Laravel Sanctum Bearer tokens or tenant API credentials passed in the `Authorization: Bearer <token>` header.

## Endpoints

### 1. List RMAs
`GET /api/v1/rmas`

**Query Parameters:**
- `status` (optional): Filter by RMA status (`pending_review`, `approved`, `refunded`, etc.)
- `branch_id` (optional): Filter by branch ID
- `per_page` (optional): Items per page (default: 15)

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "rma_number": "RMA-202610-A1B2",
        "status": "approved",
        "estimated_refund_amount": "150.00",
        "currency": "USD"
      }
    ]
  }
}
```

### 2. Create RMA
`POST /api/v1/rmas`

**Request Body:**
```json
{
  "order_id": 12,
  "return_reason": "defective",
  "resolution_type": "refund",
  "return_method": "mail_in",
  "customer_notes": "Device will not power on.",
  "items": [
    {
      "order_item_id": 24,
      "quantity": 1,
      "condition_stated": "unopened"
    }
  ]
}
```

### 3. Get RMA Detail
`GET /api/v1/rmas/{rma_number}`

### 4. Process Refund
`POST /api/v1/rmas/{rma_number}/refund`

**Request Body:**
```json
{
  "amount": 150.00,
  "method": "original_payment_method",
  "idempotency_key": "unique_idem_key_123"
}
```

### 5. List Modules
`GET /api/v1/modules`
Returns the full 90-module catalog with activation status and dependencies.
