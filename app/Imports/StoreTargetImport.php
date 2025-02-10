<?php

namespace App\Imports;

use App\StoreTarget;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StoreTargetImport implements ToModel, WithHeadingRow
{
    public function rules(): array
    {
        return [
            'storeid' => ['required'],
            'target' => ['required', 'numeric'],
            'hari' => ['required'],
            'bulan' => ['required'],
            'tahun' => ['required']
        ];

    }

    public function model(array $row)
    {
        return new StoreTarget([
            'STORE_ID' => $row['storeid'],
            'STORE_TARGET' => $row['target'],
            'HARI' => $row['hari'], 
            'BULAN' => $row['bulan'],
            'TAHUN' => $row['tahun'],
        ]);
    }
}
