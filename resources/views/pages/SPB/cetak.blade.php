
<html>
<head>
    <title>Cetak SPB Tokabe - {{$spb->customer ?? 'Tokabe'}}</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon-16x16.png')}}">
    <link rel="icon" type="image/png" href="{{asset('images/LogoTKB.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/apple-touch-icon.png')}}">
    <link href="style.css" type="text/css" rel="stylesheet" />
</head>
<style>
body{
  font-family: Bookman Old Style, sans-serif;
  letter-spacing: 1px;
  color: #000000;
  font-size:12px;

}
/* Create two equal columns that floats next to each other */
.column {
  float: left;

  width: 50%;

}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
.tess { font-size:26px; line-height:-20px;}
.noborders td {
        border:0;

    }

.no td {
        border:0;
        border-top: 0;

    }

    .tp td {

      border:0;
         border-top: 1px solid black;}

 .ts th {
  font-family: Bookman Old Style;
  letter-spacing: 1px;

 }
</style>

<body>


<div class="row">
  <div class="column">
<b style="font-size: 18px;font-family: Bookman Old Style, sans-serif;
  letter-spacing: 1px;
  font-style: normal;
  font-weight: 900;
  color: #000000;">
    Total Karya Berkah (Tokabe.id)
    </b><br>
  <!--color: #000000;">Total Karya Berkah</b><br>-->
   <div style="text-align:left; font-size: 12px;">
    JL SETIA BUDI KOMPLEK SETIA BUDI POINT NO D-10 <br> MEDAN 20132<br>
   Wa:0817-0769-999</div><br><br>
<b>Tanda Terima Barang</b>
  </div>
  <div class="column" style="text-align:right; font-size: 12px;">
    <div style="position: absolute; left: 450px; top: 10px;">
  Kepada :<br>
  Alamat :<br><br><br>
  No. Telp :
</div>

<div class="column" style="text-align: left; position: absolute; left: 560px; top: 10px;">
  <b style="font-size: 12px">{{$spb->customer}}</b><br>
  <b>{{$spb->perusahaan}}</b><br>
  <b>{{$spb->nomor_telepon}}</b>
</div>

<div style="position: absolute; left: 560px; top: 40px; display: block;">
  <b>{{$spb->alamat}}</b>
</div>
</div>

    <table width="100%" style=" border-collapse:collapse; font-size: 12px; border-bottom: 1px solid black;">
        <tr class="ts" style="border-bottom: 1px solid black;">
            <th rowspan="1">No</th>
            <th>Deskripsi</th>
            <th>Qty</th>
            <th>Keterangan</th>

        </tr>
        @foreach ($brg_spb as $index => $brg)
          @foreach ($barangs[$index] as $item)
          @php
              $nomor = $index + 1;
          @endphp
        <tr id="rowHover" class="noborders">
            <td width="1%" align="center">{{$nomor}}</td>
            <td width="25%" style="padding-left: 10px;">{{$item->nama_kategori}} {{$brg->deskripsi}}</td>
            <td width="5%" id="column_padding" align="center">{{$brg->qty}} {{$brg->satuan}}</td>
            <td width="10%" id="column_padding" align="center">{{$brg->keterangan}}</td>
        </tr>
        @endforeach
        @endforeach

             </table><br>
             <div style="position: absolute; font-size: 10px">
Dokumen-dokumen / barang-barang tersebut telah diterima dengan baik.</div>

@if($spb->nama_spb == 1)
<div style="position: absolute; width: width: 240mm; left: 0; right: 0; margin-left: auto; margin-right: auto; bottom:5px; font-size: 10px"><center>kunjungi website Kami, ikhtiarberkah.com | Ikhtiar Berkah, member of KADIN SUMUT</center></div>
@else
<div style="position: absolute; width: width: 240mm; left: 0; right: 0; margin-left: auto; margin-right: auto; bottom:5px; font-size: 10px"><center>kunjungi website Kami, tokabe.id | Total Karya Berkah, member of KADIN INDONESIA </center></div>
@endif

<div class="row">
    <div class="column" style="text-align: center; font-size: 12px">
        <div style="position: absolute; left: 100px;bottom: 10px;">
    <h4>diterima oleh</h4>
    <p><br><br></p>
   <h4>( .......................... )</h4>

  </div></div>
  <div style="position: absolute; left: 85px;bottom: 5px;">
  <h5 style="font-size: 8px"> Tanda Tangan & Nama Lengkap</h5>
</div>

  <div class="column" style="text-align: center; font-size: 12px">
         <div style="position: absolute; right:100px;bottom: 10px;">

    <h4>Medan, {{$formatTanggal}}</h4>
    <p><br><br></p>
    @if(!empty($spb->nama_penandatangan))
    <h4 style="text-decoration: underline; text-decoration-style: dashed">( {{$spb->nama_penandatangan}} )</h4>
    @elseif($spb->nama_spb == 1)
   <h4 style="text-decoration: underline; text-decoration-style: dashed">( {{auth()->user()->nama ?? 'Admin'}} )</h4>
   @else
   <h4 style="text-decoration: underline; text-decoration-style: dashed">( Oky Irawan )</h4>
   @endif

  </div>
</div></div>



    <script>
        window.load = print_d();
        function print_d(){
            window.print();
        }
    </script>
</body>
</html>