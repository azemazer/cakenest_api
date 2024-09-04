<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupcake extends Model
{
    use HasFactory;

    protected $fillable = [
        'imageSource',
        'title',
        'price',
        'quantity',
        'isAvailable',
        'isAdvertised'
    ];
}
