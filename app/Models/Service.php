<?php

namespace App\Models;

use App\Models\ProviderProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en',
        'title_ar',
        'category_id',
        'thumbnail',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'provider_service', 'service_id', 'provider_id')
            ->using(ProviderService::class)
            ->withPivot('id', 'price', 'description_en', 'description_ar', 'discount')
            ->withTimestamps();
    }
}
