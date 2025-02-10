<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetNew extends Model
{
    protected $table = 'budget.dbo.test_import';
    protected $fillable = [
        'keterangan', 'nilai', 'tahun', 'created_at', 'created_by', 'updated_at', 'updated_by'
    ];
    public $timestamps = false;
    protected $casts = [
        'keterangan' => 'string',
        'nilai' => 'decimal',
        'tahun' => 'integer',
        'created_at' => 'datetime', 'created_by' => 'string', 'updated_at' => 'datetime', 'udpated_by' => 'string'
    ];
}
