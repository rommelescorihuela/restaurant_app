<?php

declare(strict_types=1);

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class RestaurantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (! tenancy()->initialized) {
            return;
        }

        $tenantIdColumn = $model::$tenantIdColumn ?? 'restaurant_id';
        $builder->where($model->qualifyColumn($tenantIdColumn), tenant()->getTenantKey());
    }

    public function extend(Builder $builder)
    {
        $builder->macro('withoutTenancy', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }
}
