<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreTarget extends Model
{
    protected $table = 'POS_SERVER.dbo.DT_STORE_TARGET';
    protected $fillable = ['STORE_ID','STORE_TARGET','HARI','BULAN','TAHUN'];
    protected $casts = [
        'STORE_ID' => 'string',
        'STORE_TARGET' => 'integer',
        'HARI' => 'string',
        'BULAN' => 'string',
        'TAHUN' => 'string',
    ];
}
