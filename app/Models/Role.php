<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use \Spatie\Permission\Models\Role as SpatieRole;
class Role extends SpatieRole
{
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                $role->company_id = filament()->getTenant()->id;
            }
        });

        static::addGlobalScope('tenant', function ($builder) {
            if (filament()->getCurrentPanel()->getId() === 'company') {
                // $builder->where('company_id', filament()->getTenant()->id);
                $builder->where('roles.company_id', filament()->getTenant()->id);
            }
        });
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'model_id', 'role_id');
    }
}
