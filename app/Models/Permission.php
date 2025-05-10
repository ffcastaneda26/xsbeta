<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends SpatiePermission
{
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Automatically set company_id when creating a permission in the company panel
        static::creating(function ($permission) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $permission->company_id = filament()->getTenant()->id;
            }
        });

        // Apply global scope to filter permissions by tenant in the company panel
        static::addGlobalScope('tenant', function ($builder) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $builder->where('permissions.company_id', filament()->getTenant()->id);
            }
        });
    }
}
