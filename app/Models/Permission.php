<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description_en', 'description_ar'];

    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(Admin::class);
    }
}
