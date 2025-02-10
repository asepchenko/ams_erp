<?php

namespace App\Http\Requests;

use App\Brand;
use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     */
    public function authorize()
    {
        return \Gate::allows('brand_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'KODE_BRAND' => 'required|max:2|unique:DT_BRAND,KODE_BRAND',
            'NAMA_BRAND' => 'required|string|max:100',
            'DESKRIPSI' => 'required|string',
            'LOGO' => 'required|mimes:jpeg,png,jpg,bmp|max:2048'
        ];
    }
}
