<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',
        'company_id',
        'branch_id',
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar_url',
        'is_active',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'email', 'email');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isTenantOwner(): bool
    {
        return $this->role === 'tenant_owner' || $this->role === 'super_admin';
    }

    public function isBranchManager(): bool
    {
        return in_array($this->role, ['branch_manager', 'tenant_owner', 'super_admin']);
    }

    public function isFinanceStaff(): bool
    {
        return in_array($this->role, ['finance_manager', 'tenant_owner', 'super_admin']);
    }

    public function isWarehouseStaff(): bool
    {
        return in_array($this->role, ['warehouse_inspector', 'branch_manager', 'tenant_owner', 'super_admin']);
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Determine if the user can access a specific Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($panel->getId() === 'platform') {
            return $this->isSuperAdmin();
        }

        if ($panel->getId() === 'finance') {
            return $this->isFinanceStaff();
        }

        if ($panel->getId() === 'admin') {
            // All business staff can access admin panel with role-appropriate permissions
            return in_array($this->role, [
                'super_admin',
                'tenant_owner',
                'branch_manager',
                'rma_agent',
                'finance_manager',
                'warehouse_inspector',
                'repair_technician',
            ]);
        }

        return false;
    }
}
