<?php

declare(strict_types=1);

namespace App\Traits;

use App\Scopes\RestaurantScope;

trait BelongsToRestaurant
{
    public static $tenantIdColumn = 'restaurant_id';

    public function tenant()
    {
        return $this->belongsTo(config('tenancy.tenant_model'), static::$tenantIdColumn);
    }

    public static function bootBelongsToRestaurant()
    {
        static::addGlobalScope(new RestaurantScope);

        static::creating(function ($model) {
            if (! $model->getAttribute(static::$tenantIdColumn) && ! $model->relationLoaded('tenant')) {
                if (tenancy()->initialized) {
                    $model->setAttribute(static::$tenantIdColumn, tenant()->getTenantKey());
                    $model->setRelation('tenant', tenant());
                }
            }
        });
    }

    public static function getTenantKeyName(): string
    {
        return 'restaurant_id';
    }

    public function restaurant()
    {
        return $this->tenant();
    }
}
