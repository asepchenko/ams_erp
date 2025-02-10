<?php

namespace App\Http\Requests;

use App\MaterialType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialTypeRequest extends FormRequest
{
    public function authorize()
    {
        return \Gate::allows('material_edit');
    }

    public function rules()
    {
        return [
            'KODE_TYPE_MATERIAL' => 'required|exists:DT_MATERIAL_TYPE,KODE_TYPE_MATERIAL',
            'NAMA_TYPE_MATERIAL' => 'required|string|max:100',
            'DESKRIPSI' => 'required'
        ];
    }
}
