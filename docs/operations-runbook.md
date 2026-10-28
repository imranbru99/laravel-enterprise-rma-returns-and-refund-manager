# Operations Runbook

## Routine Maintenance Tasks

### Daily Operations
- Monitor failed queue jobs:
  ```bash
  php artisan queue:failed
  ```
- Check disk space in storage directory:
  ```bash
  du -sh storage/app/public
  ```

### Weekly Operations
- Purge expired session and temporary export tokens.
- Review SOC 2 audit logs for anomalous authorization attempts:
  ```bash
  php artisan tinker --execute 'echo App\Models\AuditLog::where("created_at", ">=", now()->subDays(7))->count();'
  ```

### Monthly Operations
- Run usage metering aggregation to generate tenant billing overage records.
- Rotate application cache:
  ```bash
  php artisan cache:clear
  ```
