<?php

namespace App\Http\Requests;

use App\Sourcepbo;
use Illuminate\Foundation\Http\FormRequest;

class StoreSourcepboRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Gate::allows('sourcepbo_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'KODE_SOURCEPBO' => [
                'required',
            ],
            'NAMA_SOURCEPBO' => [
                'required',
            ],
        ];
    }
}
