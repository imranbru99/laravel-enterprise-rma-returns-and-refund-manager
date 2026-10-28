<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'rma_id',
        'warehouse_id',
        'inspector_id',
        'overall_grade',
        'checklist_results',
        'notes',
        'photos',
        'estimated_restocking_value',
        'suggested_disposition',
        'passed_inspection',
        'inspected_at',
    ];

    protected function casts(): array
    {
        return [
            'checklist_results' => 'array',
            'photos' => 'array',
            'estimated_restocking_value' => 'decimal:2',
            'passed_inspection' => 'boolean',
            'inspected_at' => 'datetime',
        ];
    }

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }
}
