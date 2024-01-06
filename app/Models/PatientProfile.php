<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientProfile extends Model
{
    use HasFactory;

    protected $fillable = ['welcome'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
