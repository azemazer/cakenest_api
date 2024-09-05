<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Command extends Model
{
    use HasFactory;

    public $status = [
        "unpaid" => 0,
        "paid" => 1, 
        "canceled" => 2
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cupcakes(): HasMany
    {
        return $this->hasMany(Cupcake::class)->withPivot(['quantity']);
    }
}
