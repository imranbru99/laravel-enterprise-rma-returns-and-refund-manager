<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairJob extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'rma_id',
        'warranty_claim_id',
        'technician_id',
        'job_number',
        'diagnosis',
        'status',
        'labor_cost',
        'parts_cost',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'labor_cost' => 'decimal:2',
            'parts_cost' => 'decimal:2',
            'completed_at' => 'datetime',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class);
    }

    public function warrantyClaim(): BelongsTo
    {
        return $this->belongsTo(WarrantyClaim::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
