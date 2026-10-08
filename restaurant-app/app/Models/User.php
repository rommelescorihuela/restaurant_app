<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\BelongsToRestaurant;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    /** @use HasFactory<UserFactory> */
    use BelongsToRestaurant, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'restaurant_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->restaurant_id === null || $this->hasRole('super_admin');
        }

        if ($panel->getId() === 'app') {
            return $this->restaurant_id !== null;
        }

        return true;
    }

    public function getTenants(Panel $panel): Collection
    {
        return Collection::make([$this->tenant]);
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->restaurant_id === $tenant->id;
    }

    public function restaurant()
    {
        return $this->tenant();
    }

    public function waiterProfile(): HasOne
    {
        return $this->hasOne(WaiterProfile::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'waiter_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(WaiterAssignment::class, 'waiter_id');
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(WaiterShift::class, 'waiter_id');
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'waiter_id');
    }

    public function tableHistories(): HasMany
    {
        return $this->hasMany(TableHistory::class, 'waiter_id');
    }
}
