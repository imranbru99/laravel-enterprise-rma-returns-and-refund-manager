<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarrantyClaim extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'product_id',
        'claim_number',
        'serial_number',
        'purchase_date',
        'warranty_expires_at',
        'issue_description',
        'status',
        'resolution',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'warranty_expires_at' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function repairJobs(): HasMany
    {
        return $this->hasMany(RepairJob::class);
    }
}
