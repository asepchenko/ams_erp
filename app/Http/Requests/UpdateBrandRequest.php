<?php

namespace App\Http\Requests;

use App\Brand;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     */
    public function authorize()
    {
        return \Gate::allows('brand_edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     */
    public function rules()
    {
        return [
            'KODE_BRAND' => 'required|max:2|exists:DT_BRAND,KODE_BRAND',
            'NAMA_BRAND' => 'required|exists:DT_BRAND,NAMA_BRAND|string|max:100',
            'DESKRIPSI' => 'required|string'
        ];
    }
}
