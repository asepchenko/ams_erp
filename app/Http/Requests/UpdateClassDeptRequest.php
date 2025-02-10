<?php

namespace App\Http\Requests;

use App\ClassDept;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClassDeptRequest extends FormRequest
{
    public function authorize()
    {
        return \Gate::allows('classdept_edit');
    }

    public function rules()
    {
        return [
            'KODE_CLASS' => 'required|exists:DT_CLASS,KODE_CLASS',
            'NAMA_CLASS' => 'required|string|max:100',
            'ID_DEPT' => 'required',
            'DESKRIPSI' => 'required'
        ];
    }
}
