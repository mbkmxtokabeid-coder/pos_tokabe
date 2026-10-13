<?php

namespace App\Http\Controllers;

use App\Exports\DaftarCustomerExport;
use App\Models\Customer;
use App\Models\PenjualanTokabe;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil daftar semua invoice
        $invoices = Invoice::all();

        // Jika ada invoice yang dipilih dari formulir
        $selectedInvoice = $request->input('invoice');

        if ($selectedInvoice) {
            // Ambil data pelanggan yang terkait dengan invoice yang dipilih
            $customers = PenjualanTokabe::select('customer', 'perusahaan', 'no_telepon', 'invoice')
                ->where('invoice', $selectedInvoice)
                ->groupBy('customer', 'perusahaan', 'no_telepon', 'invoice')
                ->orderBy('customer')
                ->get();
            // dd($customers);
        } else {
            // Jika tidak ada invoice yang dipilih, ambil semua data pelanggan
            $customers = PenjualanTokabe::select('customer', 'perusahaan', 'no_telepon', 'invoice')
                ->groupBy('customer', 'perusahaan', 'no_telepon', 'invoice')
                ->orderBy('customer')
                ->get();
        }

        return view('pages.customer.daftar-pelanggan', compact('customers', 'invoices', 'selectedInvoice'));
    }

    public function getInvoices($nama)
    {
        $invoice = Invoice::where('perusahaan_id', $nama)->get();
        return response()->json($invoice);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // Ambil daftar semua invoice
        $invoices = Invoice::where('id', $request->invoice)->first();
        if($invoices == null){
            $namaInvoice = 'semua';
        } else {
            $namaInvoice = $invoices->nama_invoice ;
        }
       
        // Jika ada invoice yang dipilih dari formulir
        $selectedInvoice = $request->input('invoice');
       
        if ($selectedInvoice != null) {
            // Ambil data pelanggan yang terkait dengan invoice yang dipilih
            $customers = PenjualanTokabe::select('customer', 'perusahaan', 'no_telepon', 'invoice')
                ->where('invoice', $selectedInvoice)
                ->groupBy('customer', 'perusahaan', 'no_telepon', 'invoice')
                ->orderBy('customer')
                ->get();
        } else {
            // Jika tidak ada invoice yang dipilih, ambil semua data pelanggan
            $customers = PenjualanTokabe::select('customer', 'perusahaan', 'no_telepon', 'invoice')
                ->groupBy('customer', 'perusahaan', 'no_telepon', 'invoice')
                ->orderBy('customer')
                ->get();
        }
        if ($customers->isEmpty()) {
            Alert::error('Data Pelanggan tidak tersedia');
            return redirect(route('daftar.pelanggan'));
        } else {
            if ($request->export_type == 'excel') {
                // Menghapus karakter '-' dan tambahkan awalan '62' jika diperlukan
                $kustomer = $customers->map(function ($customer) {
                    // Menghapus karakter '-'
                    $customer->no_telepon = str_replace([' ', '-'], '', $customer->no_telepon);

                    // Cek apakah nomor telepon memiliki awalan '0', jika iya tambahkan '62'
                    if (substr($customer->no_telepon, 0, 1) == '0') {
                        $customer->no_telepon = '62' . substr($customer->no_telepon, 1);
                    }
                    // Cek apakah angka kedua adalah 6 atau 8, jika iya tambahkan '62'
                    if (strlen($customer->no_telepon) >= 2 && in_array(substr($customer->no_telepon, 1, 1), ['6', '8'])) {
                        $customer->no_telepon = '62' . substr($customer->no_telepon, 1);
                    }

                    return $customer;
                });
                // var_dump(json_encode($kustomer));die;
                // var_dump($kustomer);die;

                return Excel::download(new DaftarCustomerExport($kustomer, $selectedInvoice), 'Daftar Pelanggan ' . $namaInvoice. '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } else {
                $allInvoices = Invoice::all();
                return view('pages.customer.pdf-pelanggan', compact('customers', 'invoices', 'allInvoices'));
            }
        }

    }
}
