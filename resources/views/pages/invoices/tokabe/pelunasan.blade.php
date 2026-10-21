@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <style>
    .swal2-popup {
        width: 500px;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
    }
</style>
@endsection
@extends('layout.template')
@section('content')

<div class="page-content">
  <div class="container-fluid">

<div class="container mx-auto px-4 py-6 max-w-lg">
    <div class="bg-primary shadow-md rounded-xl p-5">
        <h1 class="text-xl font-semibold mb-4 text-center text-gray-800">
            Konfirmasi Pelunasan Invoice Tokabe
        </h1>

        <div class="mb-4">
            <p class="text-sm text-gray-700 mb-1">Nomor Invoice:</p>
            <p class="text-lg font-bold text-blue-600">{{ $invoice->nomor_invoice }}</p>
        </div>

        <div class="mb-4">
            <p class="text-sm text-gray-700 mb-1">Tanggal Invoice:</p>
            <p class="text-lg font-bold text-blue-600">
                {{ \Carbon\Carbon::parse($invoice->tgl_penjualan)->format('d F Y') }}
            </p>
        </div>

        <div class="mb-4">
            <p class="text-sm text-gray-700 mb-1">Nama Pelanggan:</p>
            <p class="text-base text-gray-800">{{ $invoice->customer }}</p>
        </div>

        @foreach ($penjualan_barang as $index => $jual)
            @foreach ($barang[$index] as $item)
                <div class="mb-4">
                    <p class="text-sm text-gray-700 mb-1">Nama Barang:</p>
                    <p class="text-base text-gray-800">{{ $item->jenis_barang }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-700 mb-1">Deskripsi Barang:</p>
                    <p class="text-base text-gray-800">{{ $jual->deskripsi_item }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-700 mb-1">Jumlah:</p>
                    <p class="text-base text-gray-800">{{ $jual->qty }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-700 mb-1">Harga:</p>
                    <p class="text-base text-gray-800">Rp. {{ number_format($jual->harga, 0, ',', '.') }}</p>
                </div>
            @endforeach
        @endforeach

        <div class="mb-4">
            <p class="text-sm text-gray-700 mb-1">Total Tagihan:</p>
            <p class="text-base text-green-600 font-semibold">Rp. {{ $invoice->formatted_total_pembayaran }}</p>
        </div>

        <div class="mb-4">
            <p class="text-sm text-gray-700 mb-1">Sisa Pembayaran:</p>
            <p class="text-base text-red-600 font-semibold">Rp. {{ $invoice->formatted_sisa_pembayaran }}</p>
        </div>

        @if($invoice->status == 'Lunas')
            <button type="button"
                class="w-full bg-secondary text-white font-bold py-2 px-4 rounded-lg cursor-not-allowed opacity-70"
                disabled>
                Invoice Sudah Lunas
            </button>
        @else
            <form action="{{ route('tokabe.status.lunas', $invoice->id) }}" method="POST" class="mb-3">
                @csrf
                <button type="submit"
                    class="w-full bg-success text-white font-bold py-2 px-4 rounded-lg transition hover:opacity-90">
                    Ubah Status Pelunasan
                </button>
            </form>

            <form action="{{ route('tokabe.status.batal', $invoice->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="alasan_batal" class="text-sm text-gray-700 mb-1 d-block">Alasan Pembatalan:</label>
                    <textarea
                        name="alasan_batal"
                        id="alasan_batal"
                        rows="3"
                        required
                        class="w-full border rounded-lg p-2 text-sm text-gray-800"
                        placeholder="Masukkan alasan pembatalan..."></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-danger text-white font-bold py-2 px-4 rounded-lg transition hover:opacity-90">
                    Batalkan Invoice
                </button>
            </form>
        @endif

        <div class="mt-4 text-center">
            <a href="{{ $invoice->is_custom == 1 ? route('list_invoice_tokabe_custom') : route('list_invoice_tokabe') }}" class="text-sm text-gray-600 underline hover:text-blue-500">Kembali ke Daftar Invoice</a>
        </div>
    </div>
</div>
@endsection

@section('plugins')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    const loggedInUser = "{{ auth()->user()->nama }}";
</script>
<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('js/pages/datatables.init.js')}}"></script>
<script src="{{asset('js/halaman/daftar-invoice.js')}}"></script>
@endsection
