<?php

namespace App\Models;

use App\Models\User;
use App\Http\Traits\FilterScopeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use FilterScopeTrait, HasFactory;

    protected $fillable = [
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
        'thumbnail',
        'price',
        'discount',
    ];

    // Accessor
    protected function attachmentPath(): Attribute
    {
        return Attribute::make(
            get: fn () => '/products' . '/' . $this->id,
        );
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(ProductReservation::class);
    }


    public function getFinalPriceAttribute()
    {
        if ($this->discount > 0)
            return $this->price - ($this->price * ($this->discount / 100));

        return null;
    }
}
