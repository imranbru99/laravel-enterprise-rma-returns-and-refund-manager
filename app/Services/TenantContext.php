<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Tenant;
use Illuminate\Support\Facades\Session;

class TenantContext
{
    protected ?Tenant $tenant = null;

    protected ?Branch $branch = null;

    public function setTenant(?Tenant $tenant): void
    {
        $this->tenant = $tenant;
        if ($tenant) {
            Session::put('current_tenant_id', $tenant->id);
        } else {
            Session::forget('current_tenant_id');
        }
    }

    public function getTenant(): ?Tenant
    {
        if ($this->tenant) {
            return $this->tenant;
        }

        $tenantId = Session::get('current_tenant_id');
        if ($tenantId) {
            $this->tenant = Tenant::find($tenantId);

            return $this->tenant;
        }

        if (auth()->check() && auth()->user()->tenant_id) {
            $this->tenant = auth()->user()->tenant;

            return $this->tenant;
        }

        return null;
    }

    public function getTenantId(): ?int
    {
        return $this->getTenant()?->id;
    }

    public function setBranch(?Branch $branch): void
    {
        $this->branch = $branch;
        if ($branch) {
            Session::put('current_branch_id', $branch->id);
        } else {
            Session::forget('current_branch_id');
        }
    }

    public function getBranch(): ?Branch
    {
        if ($this->branch) {
            return $this->branch;
        }

        $branchId = Session::get('current_branch_id');
        if ($branchId) {
            $this->branch = Branch::find($branchId);

            return $this->branch;
        }

        if (auth()->check() && auth()->user()->branch_id) {
            $this->branch = auth()->user()->branch;

            return $this->branch;
        }

        return null;
    }

    public function getBranchId(): ?int
    {
        return $this->getBranch()?->id;
    }
}
