<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetGroup extends Model
{
    protected $table = 'budget.dbo.tr_budget_new';
    protected $fillable = [
        'budget_id',
        'kode_group', 'category', 'coa', 'description', 'value_01', 'value_02', 'value_03', 'value_04', 'value_05', 'value_06',
        'value_07', 'value_08', 'value_09', 'value_10', 'value_11', 'value_12',
        'progres_01', 'progres_02', 'progres_03', 'progres_04', 'progres_05', 'progres_06',
        'progres_07', 'progres_08', 'progres_09', 'progres_10', 'progres_11', 'progres_12',
        'real_01', 'real_02', 'real_03', 'real_04', 'real_05', 'real_06',
        'real_07', 'real_08', 'real_09', 'real_10', 'real_11', 'real_12', 'total_budget', 'total_progress', 'total_realisasi', 'status', 'year', 'created_at', 'created_by'
    ];

    public $timestamps = false;
    protected $casts = [
        'budget_id' => 'integer',
        'kode_group' => 'string',
        'category' => 'string',
        'coa' => 'string',
        'description' => 'string',
        'value_01' => 'decimal',
        'value_02' => 'decimal',
        'value_03' => 'decimal',
        'value_04' => 'decimal',
        'value_05' => 'decimal',
        'value_06' => 'decimal',
        'value_07' => 'decimal',
        'value_08' => 'decimal',
        'value_09' => 'decimal',
        'value_10' => 'decimal',
        'value_11' => 'decimal',
        'value_12' => 'decimal',
        'progres_01' => 'decimal',
        'progres_02' => 'decimal',
        'progres_03' => 'decimal',
        'progres_04' => 'decimal',
        'progres_05' => 'decimal',
        'progres_06' => 'decimal',
        'progres_07' => 'decimal',
        'progres_08' => 'decimal',
        'progres_09' => 'decimal',
        'progres_10' => 'decimal',
        'progres_11' => 'decimal',
        'progres_12' => 'decimal',
        'real_01' => 'decimal',
        'real_02' => 'decimal',
        'real_03' => 'decimal',
        'real_04' => 'decimal',
        'real_05' => 'decimal',
        'real_06' => 'decimal',
        'real_07' => 'decimal',
        'real_08' => 'decimal',
        'real_09' => 'decimal',
        'real_10' => 'decimal',
        'real_11' => 'decimal',
        'real_12' => 'decimal',
        'total_budget' => 'decimal',
        'total_progress' => 'decimal',
        'total_realisasi' => 'decimal',
        'status' => 'string',
        'year'  => 'integer',
        'created_at' => 'datetime', 'created_by' => 'string'
    ];
}
