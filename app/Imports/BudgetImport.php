<?php

namespace App\Imports;

use App\BudgetNew;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BudgetImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new BudgetNew([
            'keterangan' => $row['keterangan'],
            'nilai' => $row['nilai'],
            'tahun' => $row['tahun']
        ]);
    }
}
