<?php

namespace App\Models;

use App\Enums\AdminRole;
use App\Http\Traits\FilterTrait;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Admin extends Authenticatable implements Authorizable
{
    use FilterTrait, Notifiable, HasFactory, HasApiTokens;
    protected $guard_name = 'api';
    protected $fillable = [
        'username',
        'email',
        'password',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activated' => 'boolean'
    ];

    //scope
    public function scopeSupervisors(Builder $query): void
    {
        $query->where('role', AdminRole::SUPERVISOR->value);
    }

    public function hasPermission($permission)
    {
        return $this->permissions->contains('name', $permission);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}
