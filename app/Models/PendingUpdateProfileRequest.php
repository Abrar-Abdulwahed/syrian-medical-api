<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingUpdateProfileRequest extends Model
{
    use HasFactory;
    protected $fillable = ['updates'];
    const UPDATED_AT = null;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
