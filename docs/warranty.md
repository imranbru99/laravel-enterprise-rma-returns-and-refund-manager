# Warranty Claims & Repairs Workshop

## Overview
Returns & Refund Manager includes an electronics service center module for managing manufacturer warranties, diagnostic repair jobs, and spare parts inventory.

## Capabilities
1. **Serial Number Traceability**: Validates device serial / IMEI against purchase order logs and warranty expiration dates.
2. **Diagnostic Repair Jobs**:
   - Repair statuses: `intake`, `diagnosing`, `waiting_parts`, `repaired`, `qa_passed`, `collected`.
   - Technician hourly labor costing.
   - Diagnostic fault documentation.
3. **Spare Parts Consumption**:
   - Dedicated spare parts catalog (`spare_parts` table) with SKU, unit cost, and stock quantities.
   - Automatically decrements spare part inventory upon repair job completion.
4. **Warranty Certificates**: Multilingual PDF certificates generated for extended warranty purchases.
