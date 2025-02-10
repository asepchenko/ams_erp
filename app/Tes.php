<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tes extends Model
{
    protected $table = 'testable';
    protected $fillable = [
        'image_front',
        'image_back',
        'image_beside',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];
}
