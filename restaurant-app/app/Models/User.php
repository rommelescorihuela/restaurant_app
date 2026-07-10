<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
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
