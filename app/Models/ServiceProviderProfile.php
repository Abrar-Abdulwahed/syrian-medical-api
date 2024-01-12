<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceProviderProfile extends Model
{
    use HasFactory;

    protected $fillable = ['bank_name', 'iban_number', 'swift_code', 'evidence', 'latitude', 'longitude'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
