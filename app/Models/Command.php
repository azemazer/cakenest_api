<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Command extends Model
{
    use HasFactory;

    // const CANCELED = 0;

    public static $status_list = [
        "unpaid" => 0,
        "paid" => 1, 
        "canceled" => 2
    ];

    protected $fillable = [
        "user_id",
        "total",
        "total_reductions"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cupcakes(): BelongsToMany
    {
        return $this->belongsToMany(Cupcake::class)->withPivot(['quantity']);
    }
}
