<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RejectionReason extends Model
{
    use HasFactory;

    protected $fillable = ['rejection_reason'];
    const UPDATED_AT = null;
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
