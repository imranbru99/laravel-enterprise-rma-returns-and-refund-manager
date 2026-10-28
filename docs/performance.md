# Performance Optimization & Benchmarking

## Architecture for Speed & Scale

1. **Drutogoti Performance Integration**:
   - Integrated via `App\Domain\Performance\DrutogotiPerformanceAdapter`.
   - Redis-backed result caching for active return policies, module activation flags, and tenant configuration.
   - Dynamic query memoization avoiding duplicate DB roundtrips.

2. **Database Query Optimization**:
   - Indexed foreign keys: `tenant_id`, `branch_id`, `order_id`, `rma_number`, `status`.
   - Eager loading (`with(['order', 'customer', 'items.product', 'branch'])`) throughout controllers and Filament resources prevents N+1 query bottlenecks.
   - Chunked batch processing for scheduled SLA checks and webhook dispatches.

3. **Front-End Asset Optimization**:
   - Vite bundling with production minification and tree shaking.
   - Tailwind CSS utility purging resulting in < 50KB CSS bundle.
   - Native lazy-loading on images and documents.
