# Troubleshooting & Diagnostics

## Common Issues & Solutions

### 1. "Unable to locate file in Vite manifest"
**Cause:** Assets have not been compiled or Vite dev server is not running.
**Fix:**
```bash
npm run build
```

### 2. Database Constraint Violations During Seed
**Cause:** Incomplete database reset.
**Fix:**
```bash
php artisan migrate:fresh --seed
```

### 3. Queue Jobs Not Processing
**Cause:** Queue worker is not running.
**Fix:**
```bash
php artisan queue:work redis --sleep=3 --tries=3
```

### 4. PDF Generation Fallback
**Behavior:** If native PDF rendering binaries are unavailable on a minimal host, `App\Services\PdfDocumentService` automatically returns a print-optimized, styled UTF-8 HTML document response, preventing application errors.
