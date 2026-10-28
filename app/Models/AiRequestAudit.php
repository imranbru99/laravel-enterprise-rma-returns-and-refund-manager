<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiRequestAudit extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'feature',
        'prompt_tokens',
        'completion_tokens',
        'cost_estimate',
        'input_data',
        'output_data',
        'is_mock',
    ];

    protected function casts(): array
    {
        return [
            'cost_estimate' => 'decimal:4',
            'input_data' => 'array',
            'output_data' => 'array',
            'is_mock' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
