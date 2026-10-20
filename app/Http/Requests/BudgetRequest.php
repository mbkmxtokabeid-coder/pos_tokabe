<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BudgetRequest extends FormRequest
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
            'nama_anggaran' => 'required',
            'kategori' => 'required',
            'anggaran' => 'required',
            'tanggal' => 'required',

        ];
    }
    public function messages()
    {
        return [
            'nama_anggaran.required' => 'Nama anggaran harus diisi.',
            'kategori.required' => 'Kategori harus dipilih.',
            'anggaran.required' => 'Anggaran harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
        ];
    }
}
