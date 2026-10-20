<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'kategori_id' => 'required',
            'jenis_material' => 'required',
            'kode_material' => 'required',
            'stok' => 'required',
            'harga_modal' => 'required',
            'harga_jual' => 'required',

        ];
    }
    public function messages()
    {
        return [
            'kategori_id.required' => 'Kategori ID harus diisi.',
            'jenis_material.required' => 'Jenis material harus diisi.',
            'kode_material.required' => 'Kode material harus diisi.',
            'stok.required' => 'Stok harus diisi.',
            'harga_modal.required' => 'Harga modal harus diisi.',
            'harga_jual.required' => 'Harga jual harus diisi.',
        ];
    }
}
