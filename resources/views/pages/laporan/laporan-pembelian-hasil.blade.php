<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Pembelian - {{ $vendorName }} ({{ $periodeLabel }})</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 30px 40px;
            font-family: Arial, Helvetica, sans-serif;
            color: #212529;
            background: #f8f9fa;
        }
        .report-container {
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
            padding: 35px 45px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 6px;
        }
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 14px;
            border-bottom: 1px solid #dee2e6;
        }
        .btn {
            display: inline-block;
            font-weight: 500;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            padding: 8px 18px;
            font-size: 13px;
            border-radius: 4px;
            text-decoration: none;
            border: 1px solid transparent;
        }
        .btn-primary {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-primary:hover { background-color: #0b5ed7; }
        .btn-secondary {
            color: #495057;
            background-color: #e9ecef;
            border-color: #ced4da;
        }
        .btn-secondary:hover { background-color: #dde0e3; }
        .header-section {
            text-align: center;
            margin-bottom: 28px;
        }
        .header-section h2 {
            margin: 0 0 6px;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #111827;
        }
        .header-section h4 {
            margin: 0 0 10px;
            font-size: 16px;
            font-weight: 600;
            color: #4b5563;
        }
        .header-meta {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #6b7280;
            margin-top: 15px;
            padding: 8px 12px;
            background: #f9fafb;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 15px;
        }
        th {
            background-color: #1f2937;
            color: #ffffff;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #374151;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.3px;
        }
        td {
            border: 1px solid #e5e7eb;
            padding: 9px 8px;
            vertical-align: middle;
        }
        tbody tr:nth-child(even) { background-color: #f9fafb; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 4px;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        tfoot tr td {
            background: #f3f4f6;
            font-weight: bold;
            border-top: 2px solid #9ca3af;
            border-bottom: 2px solid #9ca3af;
        }
        .signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-box .signature-space {
            height: 70px;
        }
        .signature-box .signature-name {
            font-weight: bold;
            border-bottom: 1px solid #111827;
            padding-bottom: 2px;
            display: inline-block;
            min-width: 160px;
        }
        .signature-box .signature-title {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .report-container { box-shadow: none; padding: 0; max-width: 100%; }
            .toolbar { display: none !important; }
            th { background-color: #1f2937 !important; color: #fff !important; -webkit-print-color-adjust: exact; }
            tfoot tr td { background: #f3f4f6 !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="toolbar">
            <a href="javascript:window.close()" class="btn btn-secondary">&larr; Tutup / Kembali</a>
            <button onclick="window.print()" class="btn btn-primary">&#128424; Cetak Laporan (PDF)</button>
        </div>

        <div class="header-section">
            <h2>TOKABE</h2>
            <h4>LAPORAN PEMBELIAN</h4>
            <div class="header-meta">
                <div><strong>Vendor:</strong> {{ $vendorName }}</div>
                <div><strong>Status:</strong> {{ $statusLabel }}</div>
                <div><strong>Periode:</strong> {{ $periodeLabel }}</div>
                <div><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">No</th>
                    <th>No. Invoice</th>
                    <th class="text-center" style="width: 110px;">Tanggal Pembelian</th>
                    <th class="text-center" style="width: 110px;">Tanggal JTO</th>
                    <th>Nama Vendor</th>
                    <th class="text-end" style="width: 130px;">Total Transaksi</th>
                    <th class="text-end" style="width: 130px;">Total Terbayar</th>
                    <th class="text-end" style="width: 130px;">Sisa Tagihan</th>
                    <th class="text-center" style="width: 95px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembelian as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="fw-bold">{{ $item->nomor_inv ?: '-' }}</td>
                    <td class="text-center">{{ optional($item->tanggal)->translatedFormat('d M Y') }}</td>
                    <td class="text-center">{{ optional($item->effective_tanggal_jto)->translatedFormat('d M Y') ?: '-' }}</td>
                    <td>{{ optional($item->vendor)->nama_vendor ?: '-' }}</td>
                    <td class="text-end">Rp {{ number_format($item->jumlah_harga ?? 0, 0, ',', '.') }}</td>
                    <td class="text-end" style="color: #059669;">Rp {{ number_format($item->terbayar ?? 0, 0, ',', '.') }}</td>
                    <td class="text-end" style="color: #dc2626;">Rp {{ number_format($item->sisa ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($item->status === 'Lunas')
                            <span class="badge badge-success">Lunas</span>
                        @else
                            <span class="badge badge-danger">Belum Lunas</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 30px; color: #6b7280;">
                        Tidak ada data transaksi pembelian untuk filter yang dipilih.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($pembelian->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end" style="letter-spacing: 0.5px;">GRAND TOTAL :</td>
                    <td class="text-end">Rp {{ number_format($grandTotalTransaksi, 0, ',', '.') }}</td>
                    <td class="text-end" style="color: #059669;">Rp {{ number_format($grandTotalTerbayar, 0, ',', '.') }}</td>
                    <td class="text-end" style="color: #dc2626;">Rp {{ number_format($grandTotalSisa, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>

        <div class="signatures">
            <div class="signature-box">
                <div>Dibuat Oleh,</div>
                <div class="signature-space"></div>
                <div class="signature-name">( Admin / Kasir )</div>
                <div class="signature-title">Bagian Pembelian</div>
            </div>
            <div class="signature-box">
                <div>Mengetahui,</div>
                <div class="signature-space"></div>
                <div class="signature-name">( Pimpinan / Pemilik )</div>
                <div class="signature-title">Tokabe</div>
            </div>
        </div>
    </div>
</body>
</html>
