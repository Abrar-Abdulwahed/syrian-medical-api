<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProviderProfile extends Model
{
    use HasFactory;

    protected $fillable = ['bank_name', 'iban_number', 'swift_code', 'evidence', 'latitude', 'longitude', 'payment_methods'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'payment_methods' => 'json',
    ];
}
