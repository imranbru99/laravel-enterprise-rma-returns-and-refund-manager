<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowExecution extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_id',
        'entity_type',
        'entity_id',
        'status',
        'logs',
        'executed_at',
    ];

    protected function casts(): array
    {
        return [
            'logs' => 'array',
            'executed_at' => 'datetime',
        ];
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }
}
