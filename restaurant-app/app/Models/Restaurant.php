<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Restaurant extends BaseTenant
{
    use HasDomains;

    public function domains(): HasMany
    {
        return $this->hasMany(config('tenancy.domain_model'), 'restaurant_id');
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'restaurant_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'plan',
            'data',
        ];
    }
}
