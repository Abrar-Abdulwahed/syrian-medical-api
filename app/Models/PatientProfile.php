<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatientProfile extends Model
{
    use HasFactory;
    protected $fillable = ['latitude', 'longitude', 'payment_methods'];
    protected $casts = [
        'payment_methods' => 'json',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
