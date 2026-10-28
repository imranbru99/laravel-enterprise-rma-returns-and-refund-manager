# Disaster Recovery & Backup Strategy

## Backup Policies

### 1. Database Backups
- **Frequency**: Automated hourly snapshots in production with 30-day point-in-time recovery (PITR).
- **Tooling**: Standard `mysqldump` or AWS RDS automated snapshots:
  ```bash
  mysqldump -u rma_user -p --single-transaction --quick rma_saas > rma_backup_$(date +%Y%m%d_%H%M%S).sql
  ```

### 2. File Storage Backups
- Return photos, invoices, and PDF vouchers are stored in S3-compatible object storage with versioning enabled.
- Replicated cross-region (CRR) for disaster resilience.

## Recovery Steps
1. Restore database schema and data from latest valid dump.
2. Synchronize object storage bucket.
3. Update `.env` connection variables.
4. Execute `php artisan migrate` to catch up on any pending migrations.
5. Restart PHP-FPM and queue worker daemons.
