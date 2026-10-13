<style type="text/css">
  /* style sheet for "A4" printing */
 @media print and (width: 21cm) and (height: 29.7cm) {
    @page {
       margin: 3cm;
    }
 }
 /* style sheet for "letter" printing */
 @media print and (width: 8.5in) and (height: 11in) {
    @page {
        margin: 1in;
    }

 }
   /* style sheet for "A6" printing */
@media print and (width: 10.5cm) and (height: 14.8cm) {
  @page {
      margin: 2cm;
  }

}

</style>

<html>
<head>
    <title>Cetak SPJ Tokabe - {{$spj->nomor_spj ?? ''}}</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon-16x16.png')}}">
    <link rel="icon" type="image/png" href="{{asset('images/LogoTKB.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/apple-touch-icon.png')}}">


<style type="text/css">

#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width:auto;
  font-weight: bold;
}

#customers td{
  border: 1px solid #ddd;
  padding: 8px;
}
  #customers tr:nth-child(even){background-color: #f2f2f2;}
</style>
<style>
* {
  box-sizing: border-box;
}

/* Create two equal columns that floats next to each other */
.column {
  /*float: left;*/
  /*width: 50%;*/
  /*padding: 10px;*/
  display: grid;
  justify-content: center;
   /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>
</head>

<body>

 <div style= "text-align: center; font-size: 15px;">
<u><b>SPJ (Surat Perintah Jalan)<b></u>
<p>
<center>




<div class="row">
  <div class="column" style="background-color:#ddd;">


<table border="2" id="customers">
  <tr>
    <td>SPJ</td>
    <td>: {{$spj->perusahaan}}  </td>
  </tr>

  <tr>
    <td>Tanggal</td>
    <td>: {{$current_date}}  </td>
  </tr>

  <tr>
    <td>Nomor SPJ</td>
    <td>: {{$spj->nomor_spj}}</td>
  </tr>

    <tr>
    <td>Nama Pemberi Tugas</td>
    <td>:  {{$spj->nama_pemberi_tugas}} </td>
  </tr>

  <tr>
    <td>Nama Kurir/Petugas</td>
    <td>: {{$spj->nama_kurir}}</td>
  </tr>

   <tr>
    <td>Tanggal Tugas</td>
    <td>:  {{$spj->tanggal_tugas}} </td>
  </tr>

   <tr>
    <td>Waktu Berangkat</td>
    <td>:  {{$spj->waktu_berangkat}} WIB </td>
  </tr>

   <tr>
    <td>Tujuan</td>
    <td>:  {{$spj->tujuan}} </td>
  </tr>

   <tr>
    <td>Biaya Bahan Bakar</td>
    <td>: Rp.{{$spj->biaya_bahan_bakar}} </td>
  </tr>

   <tr>
    <td>jarak_tempuh</td>
    <td>:  {{$spj->jarak_tempuh}} Km </td>
  </tr>

   <tr>
    <td>Deskripsi Barang</td>
    <td>: {{$spj->deskripsi_barang}}   </td>
  </tr>

  <tr>
    <td>Deskripsi Tugas</td>
    <td>: {{$spj->deskripsi_tugas}}   </td>
  </tr>

  <tr>
    <td>Jam Kembali</td>
    <td>: {{$spj->jam_kembali}} WIB  </td>
  </tr>

   <tr>
    <td>Status</td>
    <td>: {{$spj->status}}   </td>
  </tr>

</table>

</div>




  </div>

</div>
</div>
      <script>
        window.load = print_d();
        function print_d(){
            window.print();
        }
    </script>
</body>
</html>