# Roles, Permissions & Access Control

## Granular Roles
The platform implements explicit role-based access control (RBAC):

1. **Super Admin** (`super_admin`): Global platform administrator with access to `/platform`, tenant provisioning, plans, and system audit logs.
2. **Tenant Admin** (`tenant_admin`): Enterprise tenant owner with full control over companies, branches, return policies, module toggles, and staff.
3. **Branch Manager** (`branch_manager`): Branch-level supervisor who reviews RMAs, authorizes approvals, and manages branch inventory.
4. **Inspector / Warehouse Staff** (`inspector`): Technician performing warehouse barcode scanning, physical inspection, and condition grading.
5. **Customer Support Agent** (`support_agent`): Front-line agent assisting customers with return requests, messaging, and policy queries.
6. **Finance Officer** (`finance_manager`): Access to `/finance`, payment gateway refund settlement, credit note issuance, and journal reconciliation.
7. **Technician** (`technician`): Electronics workshop technician managing repair jobs, diagnostic checklists, and spare parts.
8. **Customer** (`customer`): End customer accessing `/portal` to track orders, initiate returns, print labels, and submit appeals.

## Multi-Panel Access Boundary
- `/platform`: Super admins only.
- `/admin`: Tenant operations, branch managers, inspectors, and support agents.
- `/finance`: Dedicated financial staff and tenant owners.
- `/portal`: Authenticated customer portal and guest order lookup.
