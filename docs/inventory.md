# Warehouse Intake, Inspection & Disposition

## Workflow

### 1. Receiving & Barcode Intake
Warehouse receiving teams scan package tracking numbers or RMA barcodes. Intake logs timestamp, receiving inspector, and package condition.

### 2. Condition Grading
Inspectors evaluate returned merchandise according to standardized tiers:
- **Grade A (Pristine / Unopened)**: Original packaging intact, zero wear, eligible for immediate restocking at 100% value.
- **Grade B (Opened / Like New)**: Box opened, complete accessories, minor cosmetic wear, restock at discounted price or outlet.
- **Grade C (Damaged / Missing Parts)**: Significant wear, missing manuals or cables, sent to refurbishment or auction.
- **Defective / Scrap**: Non-functional, damaged internals, routed to recycling or warranty return to manufacturer (RTV).

### 3. Automated Disposition Rules
Based on assigned grade and category rules, `InventoryEngineService` creates:
- `restock`: Increments sellable inventory in main warehouse bin.
- `refurbish`: Creates refurbishment work order ticket.
- `vendor_return`: Schedules supplier chargeback claim.
- `quarantine`: Holds goods in safety isolation bin.
- `scrap`: Records write-off with certificate of destruction.
