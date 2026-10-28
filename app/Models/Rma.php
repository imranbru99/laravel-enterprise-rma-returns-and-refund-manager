<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rma extends Model
{
    use BelongsToTenant, HasFactory;

    protected $table = 'rmas';

    protected $fillable = [
        'tenant_id',
        'company_id',
        'branch_id',
        'destination_branch_id',
        'customer_id',
        'order_id',
        'return_policy_id',
        'rma_number',
        'status',
        'resolution_type',
        'return_reason',
        'customer_notes',
        'internal_notes',
        'return_method',
        'carrier_code',
        'tracking_number',
        'return_shipping_fee',
        'estimated_refund_amount',
        'approved_refund_amount',
        'currency',
        'policy_version',
        'risk_score',
        'risk_reasons',
        'priority',
        'sla_due_at',
        'approved_at',
        'received_at',
        'closed_at',
        'assigned_to_user_id',
    ];

    protected function casts(): array
    {
        return [
            'return_shipping_fee' => 'decimal:2',
            'estimated_refund_amount' => 'decimal:2',
            'approved_refund_amount' => 'decimal:2',
            'risk_score' => 'integer',
            'risk_reasons' => 'array',
            'sla_due_at' => 'datetime',
            'approved_at' => 'datetime',
            'received_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function destinationBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'destination_branch_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function returnPolicy(): BelongsTo
    {
        return $this->belongsTo(ReturnPolicy::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RmaItem::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(RmaStatusHistory::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(RmaComment::class);
    }

    public function disputes(): HasMany
    {
        return $this->hasMany(RmaDispute::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }

    public function latestInspection(): HasOne
    {
        return $this->hasOne(Inspection::class)->latestOfMany();
    }

    public function dispositions(): HasMany
    {
        return $this->hasMany(Disposition::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(ReturnShipment::class);
    }

    public function latestShipment(): HasOne
    {
        return $this->hasOne(ReturnShipment::class)->latestOfMany();
    }

    public function exchangeOrder(): HasOne
    {
        return $this->hasOne(ExchangeOrder::class);
    }

    public function repairJob(): HasOne
    {
        return $this->hasOne(RepairJob::class);
    }

    /**
     * Determine valid status transitions in the RMA state machine.
     */
    public function canTransitionTo(string $targetStatus): bool
    {
        if ($this->status === $targetStatus) {
            return false;
        }

        $allowedTransitions = [
            'draft' => ['pending_review', 'cancelled'],
            'pending_review' => ['approved', 'rejected', 'cancelled'],
            'approved' => ['label_generated', 'item_shipped', 'item_received', 'cancelled'],
            'label_generated' => ['item_shipped', 'item_received', 'cancelled'],
            'item_shipped' => ['item_received', 'cancelled'],
            'item_received' => ['inspecting', 'inspection_completed'],
            'inspecting' => ['inspection_completed', 'rejected'],
            'inspection_completed' => ['resolution_pending', 'refund_processing', 'refunded', 'exchanged', 'replacement_sent', 'repaired', 'rejected'],
            'resolution_pending' => ['refund_processing', 'refunded', 'exchanged', 'replacement_sent', 'repaired', 'rejected'],
            'refund_processing' => ['refunded', 'resolution_pending'],
            'refunded' => ['closed'],
            'exchanged' => ['closed'],
            'replacement_sent' => ['closed'],
            'repaired' => ['closed'],
            'rejected' => ['closed', 'pending_review'], // Reopening allowed on appeal
            'cancelled' => ['pending_review'], // Reopening allowed with approval
            'closed' => ['pending_review'], // Reopen under dispute with authorization
        ];

        return in_array($targetStatus, $allowedTransitions[$this->status] ?? []);
    }
}
