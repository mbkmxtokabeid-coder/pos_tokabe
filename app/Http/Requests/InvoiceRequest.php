<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
            'inv' => 'required',
            'kode' => 'required',
            'tgl_jual' => 'required',
            'pelanggan' => 'required|min:2',
            'perusahaan' => 'nullable',
            'tlp' => 'required|min:2',
            'adm' => 'required',
            'order' => 'required',
            'tgl_selesai' => 'required',
            'jns_pem' => 'required',
        ];
        
    }
    
    public function messages()
    {
        return [
            'inv.required' => 'Nomor invoice harus diisi.',
            'kode.required' => 'Kode harus diisi.',
            'tgl_jual.required' => 'Tanggal penjualan harus diisi.',
            'pelanggan.required' => 'Nama pelanggan harus diisi.',
            'pelanggan.min' => 'Nama pelanggan minimal terdiri dari 2 karakter.',
            'perusahaan.required' => 'Nama perusahaan harus diisi.',
            'perusahaan.min' => 'Nama perusahaan minimal terdiri dari 2 karakter.',
            'tlp.required' => 'Nomor telepon harus diisi.',
            'tlp.regex' => 'Nomor telepon tidak valid.',
            'tlp.min' => 'Nomor telepon minimal terdiri dari 2 karakter.',
            'adm.required' => 'Admin harus diisi.',
            'order.required' => 'Order harus diisi.',
            'tgl_selesai.required' => 'Tanggal selesai harus diisi.',
            'jns_pem.required' => 'Jenis pembayaran harus diisi.',
            'barang_id[].required' => 'Barang harus dipilih.',
            'deskripsi_item[].required' => 'Deskripsi item harus diisi.',
            'satuan[].required' => 'Satuan harus diisi.',
            'hrg[].required' => 'Harga harus diisi.',
            'qty[].required' => 'Kuantitas harus diisi.',
        ];
        
    }
}
