<?php

namespace App\Http\Requests;

use App\Material;
use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
{
    public function authorize()
    {
        return \Gate::allows('material_create');
    }

    public function rules()
    {
        return [
            'KODE_MATERIAL' => 'required|unique:DT_MATERIAL,KODE_MATERIAL',
            'NAMA_MATERIAL' => 'required|string|max:100',
            'ID_TYPE_MATERIAL' => 'required',
            'DESKRIPSI' => 'required'
        ];
    }
}
