<?php

namespace App\Models;

use HashContext;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use App\Observers\CompanyObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Console\ObserverMakeCommand;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


#[ObserverMakeCommand([CompanyObserver::class])]
class Company extends Model
{
    protected $fillable = [
        'name',
        'short',
        'slug',
        'tax_id',
        'url_company',
        'email',
        'account_structure',
        'phone',
        'address',
        'num_ext',
        'num_int',
        'country_id',
        'state_id',
        'municipality',
        'city',
        'colony',
        'zipcode',
        'logo',
        'time_zone_id',
        'active',
        'user_id'
    ];


    protected static function boot()
    {
        parent::boot();

        static::created(function (Company $company) {
            $company->attachUser();
        });
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user');
    }

    public function accountingAccounts(): HasMany
    {
        return $this->hasMany(AccountingAccount::class);
    }
    public function roles(): HasMany
    {
        return $this->hasMany(related: Role::class);
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }
    public function timeZone(): BelongsTo
    {
        return $this->belongsTo(TimeZone::class);
    }

    /** Funciones de apoyo */
    public function attachUser($user = null)
    {
        $user = $user ?? Auth::user();
        if ($user) {
            $this->users()->attach($user->id);
        }

    }
    public function setUrlCompanyAttribute($value)
    {
        $this->attributes['url_company'] = strtolower($value);
    }
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function scopeUrlCompany($query, $url_company)
    {
        return $query->where('url_company', $url_company);
    }

    public function accountSubtypes(): HasMany
    {
        return $this->hasMany(AccountSubType::class);
    }

    public function accountingSingleAccounts(): HasMany
    {
        return $this->hasMany(AccountingSingleAccount::class);
    }
}
