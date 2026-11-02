<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengeluaran Beban Belanja Tokabe</title>
    <style>
        @page {
            margin: 12mm 15mm 15mm 15mm;
            size: a4 landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5pt;
            color: #1e293b;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table {
            margin-bottom: 10px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
        }
        .header-table td {
            vertical-align: middle;
            border: none;
        }
        .company-name {
            font-size: 14pt;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .report-title {
            font-size: 10.5pt;
            font-weight: 600;
            color: #475569;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .header-meta {
            text-align: right;
            font-size: 8pt;
            color: #64748b;
            line-height: 1.4;
        }
        .header-meta strong {
            color: #0f172a;
        }

        /* Filter Information Bar */
        .filter-bar {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 6px 12px;
            margin-bottom: 10px;
            font-size: 8pt;
        }
        .filter-bar td {
            border: none;
            padding: 2px 8px;
            color: #334155;
        }
        .filter-bar strong {
            color: #0f172a;
        }

        /* Summary Badges Table */
        .summary-bar {
            margin-bottom: 10px;
        }
        .summary-bar td {
            border: none;
            padding: 0 4px;
        }
        .summary-box {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 6px 10px;
        }
        .summary-label {
            font-size: 7pt;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .summary-val {
            font-size: 10.5pt;
            font-weight: bold;
            color: #0f172a;
            margin-top: 2px;
        }
        .summary-val.brand {
            color: #4338ca;
        }

        /* Main Data Table */
        .data-table {
            margin-bottom: 14px;
        }
        .data-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 6px 5px;
            border: 1px solid #1e293b;
            text-align: left;
        }
        .data-table th.text-center { text-align: center; }
        .data-table th.text-right { text-align: right; }
        .data-table td {
            padding: 5px 6px;
            font-size: 8pt;
            border: 1px solid #cbd5e1;
            vertical-align: top;
            color: #1e293b;
        }
        .data-table td.text-center { text-align: center; }
        .data-table td.text-right { text-align: right; }
        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .total-row td {
            background-color: #e2e8f0 !important;
            font-weight: bold;
            color: #0f172a;
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            padding: 6px;
            font-size: 8.5pt;
        }

        /* Signatures */
        .signature-table {
            margin-top: 18px;
            page-break-inside: avoid;
        }
        .signature-table td {
            border: none;
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 8.5pt;
        }
        .signature-space {
            height: 50px;
        }
        .signature-line {
            display: inline-block;
            min-width: 170px;
            border-bottom: 1px solid #0f172a;
            font-weight: bold;
            color: #0f172a;
            padding-bottom: 2px;
        }
        .signature-role {
            color: #64748b;
            font-size: 8pt;
            margin-top: 3px;
        }
        .footer-note {
            margin-top: 14px;
            font-size: 7.5pt;
            color: #94a3b8;
            text-align: center;
            border-top: 1px dashed #cbd5e1;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    <!-- Header Perusahaan & Dokumen -->
    <table class="header-table">
        <tr>
            @if(!empty($logoBase64))
                <td style="width: 60px;">
                    <img src="data:image/png;base64,{{ $logoBase64 }}" height="42" alt="Logo Tokabe">
                </td>
            @endif
            <td>
                <div class="company-name">TOKABE &bull; PT TOTAL KARYA BERKAH</div>
                <div class="report-title">Laporan Pengeluaran Beban Belanja</div>
            </td>
            <td class="header-meta">
                <div><strong>Waktu Cetak:</strong> {{ $tanggalCetak }}</div>
                <div><strong>Dicetak Oleh:</strong> {{ $adminCetak }} ({{ $roleCetak }})</div>
                <div><strong>Sistem:</strong> Tokabe Invoice &amp; Expense Management</div>
            </td>
        </tr>
    </table>

    <!-- Parameter Filter Laporan -->
    <div class="filter-bar">
        <table>
            <tr>
                <td style="width: 33%;"><strong>Periode:</strong> {{ $periodeLabel }}</td>
                <td style="width: 34%;"><strong>Kategori:</strong> {{ $kategoriLabel }}</td>
                <td style="width: 33%;"><strong>Sumber Anggaran:</strong> {{ $anggaranLabel }}</td>
            </tr>
        </table>
    </div>

    <!-- Ringkasan Singkat -->
    <table class="summary-bar">
        <tr>
            <td style="width: 50%;">
                <div class="summary-box">
                    <div class="summary-label">Total Beban Belanja Terdata</div>
                    <div class="summary-val brand">Rp {{ number_format($totalNominal, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 50%;">
                <div class="summary-box">
                    <div class="summary-label">Jumlah Transaksi Beban</div>
                    <div class="summary-val">{{ number_format($totalTransaksi, 0, ',', '.') }} Transaksi</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Tabel Data Beban Belanja -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 25px;">No</th>
                <th style="width: 110px;">No. Bukti</th>
                <th class="text-center" style="width: 75px;">Tanggal</th>
                <th style="width: 125px;">Kategori Beban</th>
                <th>Deskripsi Belanja</th>
                <th style="width: 120px;">Penyedia</th>
                <th class="text-right" style="width: 95px;">Nominal (Rp)</th>
                <th style="width: 125px;">Sumber Anggaran</th>
                <th class="text-center" style="width: 75px;">Admin PIC</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="font-family: monospace; font-size: 7.5pt; font-weight: bold; color: #334155;">
                        {{ $item->nomor_beban }}
                    </td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                    </td>
                    <td>
                        <strong style="color: #0f172a;">{{ $item->kategori ? $item->kategori->nama_kategori : '-' }}</strong>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #0f172a;">{{ $item->nama_beban }}</div>
                        @if($item->keterangan)
                            <div style="font-size: 7pt; color: #64748b; font-style: italic; margin-top: 1px;">
                                "{{ $item->keterangan }}"
                            </div>
                        @endif
                    </td>
                    <td>
                        {{ $item->penerima ?: '-' }}
                    </td>
                    <td class="text-right" style="font-weight: 600; color: #0f172a; font-family: monospace;">
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>
                    <td>
                        @if($item->anggaran)
                            <span style="color: #047857; font-weight: 600;">{{ $item->anggaran->nama_budget }}</span>
                        @else
                            <span style="color: #64748b;">Non-Anggaran</span>
                        @endif
                    </td>
                    <td class="text-center" style="color: #475569;">
                        {{ $item->admin ? $item->admin->nama : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 24px; color: #94a3b8; font-style: italic;">
                        Tidak ada catatan pengeluaran beban belanja untuk filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($records) > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="6" class="text-right" style="letter-spacing: 0.5px;">
                        TOTAL PENGELUARAN BEBAN BELANJA:
                    </td>
                    <td class="text-right" style="font-size: 9.5pt; font-family: monospace; color: #1e1b4b;">
                        Rp {{ number_format($totalNominal, 0, ',', '.') }}
                    </td>
                    <td colspan="2" style="font-size: 7.5pt; color: #475569;">
                        {{ count($records) }} transaksi tercatat
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- Tanda Tangan Pengesahan -->
    <table class="signature-table">
        <tr>
            <td>
                <div>Disiapkan Oleh,</div>
                <div class="signature-space"></div>
                <div>
                    <span class="signature-line">{{ $adminCetak }}</span>
                </div>
                <div class="signature-role">Admin / PIC Tokabe</div>
            </td>
            <td>
                <div>Mengetahui &amp; Menyetujui,</div>
                <div class="signature-space"></div>
                <div>
                    <span class="signature-line">. . . . . . . . . . . . . . . . . . . .</span>
                </div>
                <div class="signature-role">Pimpinan / Keuangan Tokabe</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini dihasilkan secara otomatis oleh Tokabe POS &amp; Invoice System pada {{ $tanggalCetak }}.
    </div>

</body>
</html>
