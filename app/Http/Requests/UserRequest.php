<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'namaKaryawan' => 'required|min:2',
            'email' => 'required|email|min:2',
            'password' => 'required|min:5',
            're_password' => 'required|min:5|confirmed',
            'no_telp' => 'required|regex:/^[0-9\+\s]+$/|min:5',
            'role' => 'required|in:Pemilik,Admin',
        ];
    }
    public function messages()
    {
        return [
            'namaKaryawan.required' => 'Kolom nama karyawan harus diisi.',
            'namaKaryawan.min' => 'Kolom nama karyawan minimal harus memiliki :min karakter.',
            'email.required' => 'Kolom email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.min' => 'Kolom email minimal harus memiliki :min karakter.',
            'password.required' => 'Kolom password harus diisi.',
            'password.min' => 'Kolom password minimal harus memiliki :min karakter.',
            're_password.confirmed' => 'Konfirmasi password tidak sesuai dengan password.',
            're_password.required' => 'Kolom konfirmasi password harus diisi.',
            're_password.min' => 'Kolom konfirmasi password minimal harus memiliki :min karakter.',
            'no_telp.required' => 'Kolom nomor telepon harus diisi.',
            'no_telp.regex' => 'Format nomor telepon tidak valid.',
            'no_telp.min' => 'Kolom nomor telepon minimal harus memiliki :min karakter.',
            'role.required' => 'Kolom role harus dipilih.',
            'role.in' => 'Role yang dipilih harus Pemilik atau Admin.',
        ];
    }
}
