<?php

namespace App\Imports;

use App\BudgetGroup;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StoreBudgetImport implements ToCollection, WithHeadingRow
{
    // public function model(array $row)
    // {
    //     return new BudgetGroup([
    //         'kode_group' => $row['group'],
    //         'description' => $row['keterangan'],
    //         'value_01' => $row['nilai_jan'],
    //         'value_02' => $row['nilai_feb'],
    //         'value_03' => $row['nilai_mar'],
    //         'value_04' => $row['nilai_apr'],
    //         'value_05' => $row['nilai_mei'],
    //         'value_06' => $row['nilai_jun'],
    //         'value_07' => $row['nilai_jul'],
    //         'value_08' => $row['nilai_agt'],
    //         'value_09' => $row['nilai_sept'],
    //         'value_10' => $row['nilai_okt'],
    //         'value_11' => $row['nilai_nov'],
    //         'value_12' => $row['nilai_des'],
    //         'year' => $row['tahun']
    //     ]);
    // }
    public function collection(Collection $rows)
    {
        $group = $rows->toArray();
        $kodegroup = $group[0]['group'];
        BudgetGroup::where('kode_group', $kodegroup)->delete();
        foreach ($rows as $row) {
            BudgetGroup::create([
                'budget_id' => mt_rand(100000, 999999),
                'kode_group' => strtoupper($row['group']),
                'description' => strtoupper($row['keterangan']),
                'value_01' => $row['nilai_jan'],
                'value_02' => $row['nilai_feb'],
                'value_03' => $row['nilai_mar'],
                'value_04' => $row['nilai_apr'],
                'value_05' => $row['nilai_mei'],
                'value_06' => $row['nilai_jun'],
                'value_07' => $row['nilai_jul'],
                'value_08' => $row['nilai_agt'],
                'value_09' => $row['nilai_sept'],
                'value_10' => $row['nilai_okt'],
                'value_11' => $row['nilai_nov'],
                'value_12' => $row['nilai_des'],
                'progres_01' => 0,
                'progres_02' => 0,
                'progres_03' => 0,
                'progres_04' => 0,
                'progres_05' => 0,
                'progres_06' => 0,
                'progres_07' => 0,
                'progres_08' => 0,
                'progres_09' => 0,
                'progres_10' => 0,
                'progres_11' => 0,
                'progres_12' => 0,
                'real_01' => 0,
                'real_02' => 0,
                'real_03' => 0,
                'real_04' => 0,
                'real_05' => 0,
                'real_06' => 0,
                'real_07' => 0,
                'real_08' => 0,
                'real_09' => 0,
                'real_10' => 0,
                'real_11' => 0,
                'real_12' => 0,
                'total_budget' => $row['nilai_jan']  + $row['nilai_feb'] + $row['nilai_mar'] + $row['nilai_apr'] + $row['nilai_mei'] +
                    $row['nilai_jun'] + $row['nilai_jul'] + $row['nilai_agt'] + $row['nilai_sept'] + $row['nilai_okt'] + $row['nilai_nov'] + $row['nilai_des'],
                'total_progress' => 0,
                'total_realisasi' => 0,
                'year' => $row['tahun'],
                'status' => 'open',
                'created_at' => now(),
                'created_by' => auth()->user()->nik
            ]);
        }
    }
}
