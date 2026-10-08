<?php

declare(strict_types=1);

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;

class Domain extends BaseDomain
{
    public static function tenantIdColumn(): string
    {
        return 'restaurant_id';
    }

    public function tenant()
    {
        return $this->belongsTo(config('tenancy.tenant_model'), 'restaurant_id');
    }
}
