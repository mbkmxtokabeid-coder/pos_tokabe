<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan {{$namaInvoice}} {{$status_invoice}}</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" >
  
  <style>
    h4 {
      font-family: Arial, sans-serif;
      font-size: 30px;
      font-weight: 600;
      color: #333;
      margin: 20px 0;
    }
    .table {
      font-size: 12px;
      font-family: Arial, sans-serif;
    }
    .table-striped tbody tr:nth-of-type(even) {
      background-color: #4f6a9c;
    }
    /* Batas */
    .signatures {
        display: flex;
        justify-content: space-evenly;
        margin-top: 40px;
    }
    .signature div{
        width: 100px;
        height: 100px;
    }
    .signature {
        text-align: center;
    }
    .signature-name {
        margin-top: 20px;
    }
    .underline {
        text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="container-fluid mt-4">
    <div class="row justify-content-center">
      <h4 class="col m-4 text-center">Laporan Invoice {{$namaInvoice}} {{$status_invoice}}</h4>
    </div>
    <div class="row">
        <table class="table table-bordered">
         <thead class="thead-dark">
          <tr>
           <th scope="col">NO</th>
           <th scope="col">NO INVOICE</th>
           <th scope="col">TANGGAL</th>
           <th scope="col">CUSTOMER</th>
           <th scope="col">PERUSAHAAN</th>
           <th scope="col">NOMOR HP</th>
           <th scope="col">DESKRIPSI ITEM</th>
           <th scope="col">JUMLAH ITEM</th>
           <th scope="col">TOTAL</th>
           <th scope="col">STATUS</th>
           <th scope="col">POTONGAN/DISKON/PPN</th>
           <th scope="col">GRAND TOTAL</th>
          </tr>
         </thead>
        <tbody>
        @foreach ($penjualans as $jual)
         <tr>
          <td>{{$loop->iteration}}</td>
          <td>{{$jual->nomor_invoice}}</td>
          <td>{{$jual->formatted_tgl_penjualan}}</td>
          <td>{{$jual->customer}}</td>
          <td>{{$jual->perusahaan}}</td>
          <td>{{$jual->no_telepon ?? '-'}}</td>
          
          <!-- Kolom Deskripsi Item (jenis_barang & deskripsi_item) -->
          <td class="p-0" style="vertical-align: top;">
             <table class="table mb-0" style="background: transparent; border: none;">
                 @forelse($jual->items ?? [] as $item)
                 <tr>
                     <td style="border: none; border-bottom: {{ !$loop->last ? '1px solid #dee2e6' : 'none' }};">
                        <strong>{{ $item->jenis_barang }}</strong><br>
                        <span style="font-size: 10.5px; color: #555;">{{ $item->deskripsi_item }}</span>
                     </td>
                 </tr>
                 @empty
                 <tr><td style="border: none;">-</td></tr>
                 @endforelse
             </table>
          </td>
          
          <!-- Kolom Jumlah Item (qty & satuan) -->
          <td class="p-0" style="vertical-align: top;">
             <table class="table mb-0" style="background: transparent; border: none; height: 100%;">
                 @forelse($jual->items ?? [] as $item)
                 <tr>
                     <td style="border: none; border-bottom: {{ !$loop->last ? '1px solid #dee2e6' : 'none' }}; vertical-align: middle;">
                        {{ $item->qty }} {{ $item->satuan }}
                     </td>
                 </tr>
                 @empty
                 <tr><td style="border: none;">-</td></tr>
                 @endforelse
             </table>
          </td>
          
          <td>{{$jual->formatted_total_harga}}</td>
          <td>{{$jual->status}}</td>
          <td>{{$jual->lain}}</td>
          <td>{{$jual->formatted_total_pembayaran}}</td>
         </tr>
        @endforeach
        
        <!-- Baris Footer / Jumlah -->
        <tr>
          <!-- Menggunakan colspan="10" agar tulisan Jumlah sejajar dengan kolom sebelum Grand Total -->
          <td colspan="10" class="text-right text-bold" style="font-weight: bold;">Jumlah :</td>
          <td colspan="2" class="text-bold" style="font-weight: bold;">{{$formatGrandTotal}}</td>
         </tr>
        </tbody>
       </table>
      </div>
      
      <div class="signatures">
       <div class="col-3">
        <p class="text-center">{{ !empty($namaPerusahaan) ? $namaPerusahaan : 'Total Karya Berkah' }}, {{$tanggal}}</p>

        <p class="text-center">Disiapkan Oleh,</p>
        <p class="text-center">&nbsp;</p>
        <p class="text-center">&nbsp;</p>
        <p class="text-center" style="text-decoration: underline;">{{auth()->user()->nama ?? 'Admin'}}</p>
        <p class="text-center">{{auth()->user()->role ?? 'Staff'}}</p>
       </div>
       
       <div class="col-3">
        <p class="text-center">&nbsp;</p>
        <p class="text-center">Diketahui Oleh,</p>
        <p class="text-center">&nbsp;</p>
        <p class="text-center">&nbsp;</p>
        <p class="text-center" style="text-decoration: underline;">. . . . . . . . . . . .</p>
        <p class="text-center">Pimpinan</p>
       </div>
      </div>

    </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>