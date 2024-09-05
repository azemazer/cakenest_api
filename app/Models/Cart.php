<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];

    public function carts(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function cupcakes(): BelongsToMany
    {
        return $this->belongsToMany(Cupcake::class)->withPivot(['quantity']);
    }
}
