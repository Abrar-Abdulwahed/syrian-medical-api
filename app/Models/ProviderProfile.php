<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProviderProfile extends Model
{
    use HasFactory;

    protected $fillable = ['bank_name', 'iban_number', 'swift_code', 'evidence', 'latitude', 'longitude'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)->withPivot('price', 'description', 'discount', 'time');;
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
