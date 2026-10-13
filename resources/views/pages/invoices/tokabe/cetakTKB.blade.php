<html>

<head>
  <title>{{$penjualan->nomor_invoice ?? 'Invoice Tokabe'}} - {{$penjualan->customer ?? 'Tokabe'}}</title>
  <!-- App favicon -->
  <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicon-32x32.png')}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon-16x16.png')}}">
  <link rel="icon" type="image/png" href="{{asset('images/LogoTKB.png')}}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/apple-touch-icon.png')}}">
  <link href="style.css" type="text/css" rel="stylesheet" />
</head>
<style>
  body {
    font-family: Bookman Old Style, sans-serif;
    letter-spacing: 1px;
    color: #000000;
    font-size: 12px;

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

  .tess {
    font-size: 26px;
    line-height: -20px;
  }

  .noborders td {
    border: 0;

  }

  .no td {
    border: 0;
    border-top: 0;

  }

  .tp td {

    border: 0;
    border-top: 1px solid black;
  }

  .ts th {
    font-family: Bookman Old Style, sans-serif;
    letter-spacing: 1px;

  }
</style>

<body>


  <div class="row">
    <div class="column">
      <b style="font-size: 16px;font-family: Bookman Old Style, sans-serif;
  letter-spacing: 1px;
  font-style: normal;
  font-weight: 900;
  color: #000000;">{{$toko}}</b><br>
      <div style="text-align:left; font-size: 12px;">
        JL SETIA BUDI KOMPLEK SETIA BUDI POINT NO D-10 <br> KEL TANJUNG SARI MEDAN 20132<br>
        Wa: 08112272727
        </div><br><br>
      Nomor Invoice : {{$penjualan->nomor_invoice}}
    </div>
    <div class="column" style="text-align:right; font-size: 12px;">
      <div style="position: absolute; left: 400px;top: 10px;">
        Kepada :<br>
        
        No. Telp :</div>
    </div>
    <div class="column" style="text-align:left;">
      <div style="position: absolute; left:560px;top: 10px;">
        <b style="font-size: 12px">{{$penjualan->customer}}</b><br>
        
        <b>{{$penjualan->no_telepon}}</b>
      </div>
    </div>

    <table width="100%" style=" border-collapse:collapse; font-size: 12px; border-bottom: 1px solid black;">
      <tr class="ts" style="border-bottom: 1px solid black;">
        <th rowspan="1">No</th>
        <th>Deskripsi Barang</th>
        <th>Qty</th>
        <th>Harga</th>
        <th>Jumlah</th>
      </tr>

      @foreach ($penjualan_barang as $index => $jual)
          @foreach ($barang[$index] as $item)
          @php
              $nomor = $index + 1;
          @endphp
          <tr id="rowHover" class="noborders">
            <td width="1%" align="center"> {{$nomor}}</td>
            <td width="25%" style="padding-left: 10px;"> {{$item->jenis_barang}} {{$jual->deskripsi_item}}</td>
            <td width="5%" id="column_padding" align="center"> {{$jual->qty}} {{$jual->satuan}}</td>
            <td width="10%" id="column_padding" align="center">Rp.{{$hargaMod[$index]}}</td>
            <td width="10%" id="column_padding" align="center">Rp.{{$jumlahHarga[$index]}}</td>

          </tr>
          @endforeach
      @endforeach

    </table>
    <div style="position: absolute; bottom: 220px; font-size: 10px">{{$norek}}</div>

    <div style="position: absolute; right: 10px; bottom: 65px; min-width: 250px;">
        <table style="width: 100%; text-align: right; font-size: 12px; border-collapse: collapse;">
            <tr>
                <td style="text-align: left; padding: 1px 5px;"><b>Total Harga</b></td>
                <td style="text-align: right; padding: 1px 5px;"><b>Rp. {{ number_format($penjualan->total_harga, 0, ',', '.') }}</b></td>
            </tr>
            @php
                $hasPpn = !empty($penjualan->ppn) && (float)$penjualan->ppn > 0;
                $hasDiskon = !empty($penjualan->diskon) && (float)$penjualan->diskon > 0;
                $hasPotongan = !empty($penjualan->potongan) && (float)$penjualan->potongan > 0;
                $hasPph = !empty($penjualan->pph) && (float)$penjualan->pph > 0;
                
                $autoPpnNominal = 0;
                $autoPpnPersen = 0;
                if (!$hasPpn && !$hasDiskon && !$hasPotongan && !$hasPph && $penjualan->total_pembayaran > $penjualan->total_harga && $penjualan->total_harga > 0) {
                    $autoPpnNominal = $penjualan->total_pembayaran - $penjualan->total_harga;
                    $autoPpnPersen = round(($autoPpnNominal / $penjualan->total_harga) * 100);
                    $hasPpn = true;
                }
            @endphp

            @if ($hasDiskon)
            <tr>
                <td style="text-align: left; padding: 1px 5px;"><b>Diskon ({{$penjualan->diskon}}%)</b></td>
                <td style="text-align: right; padding: 1px 5px;"><b>- Rp. {{ number_format(($penjualan->diskon / 100) * $penjualan->total_harga, 0, ',', '.') }}</b></td>
            </tr>
            @endif
            @if ($hasPotongan)
            <tr>
                <td style="text-align: left; padding: 1px 5px;"><b>Spesial Diskon</b></td>
                <td style="text-align: right; padding: 1px 5px;"><b>- Rp. {{ number_format($penjualan->potongan, 0, ',', '.') }}</b></td>
            </tr>
            @endif
            @if ($hasPpn)
            @php
                $ppnVal = !empty($penjualan->ppn) ? $penjualan->ppn : $autoPpnPersen;
                $ppnAmount = !empty($penjualan->ppn) ? (($penjualan->ppn / 100) * $penjualan->total_harga) : $autoPpnNominal;
            @endphp
            <tr>
                <td style="text-align: left; padding: 1px 5px;"><b>PPN ({{$ppnVal}}%)</b></td>
                <td style="text-align: right; padding: 1px 5px;"><b>+ Rp. {{ number_format($ppnAmount, 0, ',', '.') }}</b></td>
            </tr>
            @endif
            @if ($hasPph)
            <tr>
                <td style="text-align: left; padding: 1px 5px;"><b>PPH ({{$penjualan->pph}}%)</b></td>
                <td style="text-align: right; padding: 1px 5px;"><b>- Rp. {{ number_format(($penjualan->pph / 100) * $penjualan->total_harga, 0, ',', '.') }}</b></td>
            </tr>
            @endif
            <tr>
                <td style="text-align: left; padding: 1px 5px; border-top: 1px dashed #000;"><b>Total Pembayaran</b></td>
                <td style="text-align: right; padding: 1px 5px; border-top: 1px dashed #000;"><b>Rp. {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}</b></td>
            </tr>
            @if (!empty($penjualan->dp) && (float)$penjualan->dp > 0)
            <tr>
                <td style="text-align: left; padding: 1px 5px;"><b>DP / Panjar</b></td>
                <td style="text-align: right; padding: 1px 5px;"><b>- Rp. {{ number_format($penjualan->dp, 0, ',', '.') }}</b></td>
            </tr>
            @endif
            <tr>
                <td style="text-align: left; padding: 1px 5px; border-top: 1px dashed #000;"><b>Sisa Bayar</b></td>
                <td style="text-align: right; padding: 1px 5px; border-top: 1px dashed #000;"><b>Rp. {{$sisaBayarMod}}</b></td>
            </tr>
        </table>
    </div>

    <div style="position: absolute; width: 240mm; left: 0; right: 0; margin-left: auto; margin-right: auto; bottom:5px; font-size: 10px">
      <center><i>Terima Kasih telah mendukung UMKM kami, dengan senang hati melayani.</i></center>
    </div>

    <div class="row">
      <div class="column" style="text-align: center; font-size: 12px">
        <div style="position: absolute; left: 20px;bottom: 10px;">
          <h4>diterima oleh</h4>
          <p><br><br><br><br><br><br></p>
          <h4>( {{$penjualan->customer}} )</h4>
        </div>
      </div>

      <div class="column" style="text-align: center; font-size: 12px">
        <div style="position: absolute; right:340px;bottom: 10px;">

          <h4>Medan,{{$formatTanggal}}</h4>
          <p><br><br><br><br><br><br></p>
          <h4>{{$admin}}</h4>
        </div>
      </div>
    </div>



    <script>
      window.load = print_d();

      function print_d() {
        window.print();
      }
    </script>
</body>

</html>