<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Data Customer</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" >
  {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> --}}


  <style>
    h5 {
      /* gaya huruf dan ukuran yang sama dengan Bootstrap */
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

  <div class="container">
    <div class="row justify-content-center">
      <h5 class="col m-4 text-center">Daftar Data Pelanggan</h5>
    </div>
    <div class="row">
        <table class="table">
         <thead class="thead-dark">
          <tr>
           <th scope="col">NO</th>
           <th scope="col">Nama</th>
           <th scope="col">Perusahaan</th>
           <th scope="col">No Telepon</th>
           <th scope="col">Kategori</th>
          </tr>
         </thead>
        <tbody>
        @foreach ($customers as $cust)
         <tr>
          <td>{{$loop->iteration}}</td>
          <td>{{$cust->customer}}</td>
          <td>{{$cust->perusahaan}}</td>
          <td>{{$cust->no_telepon}}</td>
          @if($invoices)
          <td>{{ $invoices->nama_invoice }}</td>
          @elseif(isset($allInvoices))
          <td>{{ optional($allInvoices->where('id', $cust->invoice)->first())->nama_invoice ?? '-' }}</td>
          @else
          <td>-</td>
          @endif
         </tr>
        @endforeach
        </tbody>
       </table>
      </div>
     {{-- <div class="signatures">
      <!-- <div class="col-2"></div> -->
      <div class="col-3">
        <p class="text-center">Total Karya Berkah, {{$tanggal}}</p>

        <p class="text-center">Disiapkan Oleh,</p>
        <p class="text-center">&nbsp;</p> <!-- Gunakan paragraf kosong untuk membuat jarak -->
        <p class="text-center">&nbsp;
        <p class="text-center" style="text-decoration: underline;">{{auth()->user()->nama}}</p>
        <p class="text-center">{{auth()->user()->role}}</p>

      </div>
      <div class="col-3">
        <p class="text-center">&nbsp;</p>

        <p class="text-center">Diketahui Oleh,</p>
        <p class="text-center">&nbsp;</p> <!-- Gunakan paragraf kosong untuk membuat jarak -->
        <p class="text-center">&nbsp;
        <p class="text-center" style="text-decoration: underline;">. . . . . . . . . . . .</p>
        <p class="text-center">Pimpinan</p>
      </div>
     </div> --}}
    </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>