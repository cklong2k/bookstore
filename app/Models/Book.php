<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'images' => 'json',
        'price' => 'float',
        'quantity' => 'integer',
        'publicationDate' => 'date:Y-m-d',
    ];
}
