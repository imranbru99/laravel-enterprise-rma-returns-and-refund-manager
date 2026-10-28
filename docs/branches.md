# Multi-Company & Multi-Branch Architecture

## Enterprise Hierarchy

Returns & Refund Manager supports complex multi-tiered enterprise organizations:
- **Companies**: Distinct legal entities or subsidiaries within the same tenant.
- **Regions**: Geographic clusters (e.g., Dhaka Division, Chattogram Division, Sylhet Division).
- **Branches**: Retail storefronts, service centers, and flagship outlets.
- **Warehouses**: Receiving points, return staging bins, quarantine areas, and restock shelves.

## Omnichannel Return-At-Different-Branch (BORIS)
A core strength of the platform is Buy-Online-Return-In-Store (BORIS) and cross-branch drop-offs:
1. An order placed online from the central warehouse or Dhaka branch can be returned physically at the Chattogram branch or Sylhet service center.
2. The RMA records both:
   - `branch_id`: Origin branch where the order was placed or assigned.
   - `destination_branch_id`: Receiving branch where the customer dropped off or shipped the item.
3. Inventory movements are automatically generated to reflect the intake at the destination branch's return staging warehouse.
4. If the item must be returned to the central warehouse, an Inter-Branch Transfer record is created.
