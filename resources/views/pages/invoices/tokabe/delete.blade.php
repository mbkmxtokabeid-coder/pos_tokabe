@extends('layout.template')

@section('head')
<style>
    /* =========================================================
       TOKABE DESIGN TOKENS & STYLING (DESIGN.md)
       Dark Theme: #0C0F14 App BG, #12161D Surface, #232A35 Border
       ========================================================= */
    :root {
        --del-bg-surface: var(--bg-surface, #12161D);
        --del-bg-surface-2: #181D26;
        --del-bg-surface-3: #1F2633;
        --del-border-subtle: var(--border-subtle, #232A35);
        --del-border-default: #334155;
        --del-text-primary: var(--text-primary, #F1F5F9);
        --del-text-secondary: var(--text-secondary, #CBD5E1);
        --del-text-tertiary: var(--text-tertiary, #94A3B8);
        --del-danger: var(--danger, #EF4444);
        --del-danger-hover: #DC2626;
        --del-danger-subtle: rgba(239, 68, 68, 0.12);
        --del-danger-text: #FCA5A5;
        --del-danger-border: rgba(239, 68, 68, 0.35);
        --del-success: var(--success, #10B981);
        --del-success-subtle: rgba(16, 185, 129, 0.12);
        --del-success-text: #6EE7B7;
        --del-warning: var(--warning, #F59E0B);
        --del-warning-subtle: rgba(245, 158, 11, 0.12);
        --del-warning-text: #FCD34D;
        --del-brand: var(--brand, #6366F1);
        --del-brand-subtle: rgba(99, 102, 241, 0.12);
        --del-radius-md: 8px;
        --del-radius-lg: 12px;
        --del-radius-xl: 16px;
    }

    .del-page-wrapper {
        max-width: 840px;
        margin: 0 auto;
        padding: 24px 16px 48px;
    }

    /* Top Breadcrumb & Navigation */
    .del-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        gap: 12px;
        flex-wrap: wrap;
    }
    .del-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--del-text-tertiary);
    }
    .del-breadcrumb a {
        color: var(--del-text-secondary);
        text-decoration: none;
        transition: color 150ms;
    }
    .del-breadcrumb a:hover {
        color: var(--del-text-primary);
        text-decoration: underline;
    }
    .del-breadcrumb-separator {
        color: var(--del-border-default);
    }
    .del-breadcrumb-current {
        color: var(--del-danger-text);
        font-weight: 500;
    }

    /* Main Confirmation Card */
    .del-card {
        background: var(--del-bg-surface);
        border: 1px solid var(--del-border-subtle);
        border-radius: var(--del-radius-xl);
        box-shadow: 0 16px 36px -8px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.03);
        overflow: hidden;
    }

    /* Card Header */
    .del-card-header {
        padding: 24px 28px;
        border-bottom: 1px solid var(--del-border-subtle);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        background: linear-gradient(180deg, rgba(239, 68, 68, 0.05) 0%, transparent 100%);
    }
    .del-header-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .del-header-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--del-danger-subtle);
        border: 1px solid var(--del-danger-border);
        color: var(--del-danger);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .del-header-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--del-text-primary);
        margin: 0 0 4px 0;
        line-height: 1.25;
    }
    .del-header-subtitle {
        font-size: 13px;
        color: var(--del-text-tertiary);
        margin: 0;
    }

    /* Card Body */
    .del-card-body {
        padding: 28px;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* Destructive Alert Banner - DESIGN.md §15.1 */
    .del-alert-box {
        background: var(--del-danger-subtle);
        border: 1px solid var(--del-danger-border);
        border-radius: var(--del-radius-lg);
        padding: 16px 20px;
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }
    .del-alert-icon {
        color: var(--del-danger);
        font-size: 22px;
        flex-shrink: 0;
        line-height: 1.2;
        margin-top: 1px;
    }
    .del-alert-content h4 {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--del-danger-text);
        margin: 0 0 6px 0;
    }
    .del-alert-content p {
        font-size: 13px;
        line-height: 1.55;
        color: var(--del-danger-text);
        margin: 0;
        opacity: 0.95;
    }

    /* Info Grid (2 Columns) */
    .del-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }
    @media (max-width: 640px) {
        .del-info-grid {
            grid-template-columns: 1fr;
        }
    }
    .del-info-item {
        background: var(--del-bg-surface-2);
        border: 1px solid var(--del-border-subtle);
        border-radius: 10px;
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .del-info-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--del-text-tertiary);
        margin: 0;
    }
    .del-info-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--del-text-primary);
        margin: 0;
        word-break: break-word;
    }
    .del-info-sub {
        font-size: 12px;
        color: var(--del-text-tertiary);
        margin-top: 2px;
    }

    /* Pill Badges - DESIGN.md §14 */
    .del-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 24px;
        padding: 0 10px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 9999px;
        border: 1px solid transparent;
        width: fit-content;
    }
    .del-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }
    .del-badge--success {
        color: var(--del-success-text);
        background: var(--del-success-subtle);
        border-color: rgba(16, 185, 129, 0.35);
    }
    .del-badge--warning {
        color: var(--del-warning-text);
        background: var(--del-warning-subtle);
        border-color: rgba(245, 158, 11, 0.35);
    }
    .del-badge--danger {
        color: var(--del-danger-text);
        background: var(--del-danger-subtle);
        border-color: var(--del-danger-border);
    }

    /* Items Section & Table - DESIGN.md §13 */
    .del-items-section {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .del-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .del-section-title {
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--del-text-tertiary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .del-table-wrapper {
        border: 1px solid var(--del-border-subtle);
        border-radius: 10px;
        overflow: hidden;
        background: var(--del-bg-surface-2);
    }
    .del-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin: 0;
    }
    .del-table th {
        background: var(--del-bg-surface-3);
        color: var(--del-text-tertiary);
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 10px 14px;
        border-bottom: 1px solid var(--del-border-subtle);
        text-align: left;
    }
    .del-table td {
        padding: 12px 14px;
        border-bottom: 1px solid var(--del-border-subtle);
        color: var(--del-text-primary);
        vertical-align: middle;
    }
    .del-table tr:last-child td {
        border-bottom: none;
    }
    .del-table td.numeric, .del-table th.numeric {
        text-align: right;
        font-variant-numeric: tabular-nums;
    }

    /* Financial Breakdown Box */
    .del-finance-card {
        background: var(--del-bg-surface-2);
        border: 1px solid var(--del-border-subtle);
        border-radius: 10px;
        padding: 16px 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .del-finance-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        color: var(--del-text-secondary);
    }
    .del-finance-row--total {
        padding-top: 10px;
        margin-top: 4px;
        border-top: 1px dashed var(--del-border-default);
        font-size: 15px;
        font-weight: 700;
        color: var(--del-text-primary);
    }
    .del-finance-value {
        font-variant-numeric: tabular-nums;
        font-weight: 600;
    }
    .del-finance-value--green {
        color: var(--del-success-text);
        font-size: 17px;
    }
    .del-finance-value--red {
        color: var(--del-danger-text);
        font-size: 15px;
    }

    /* Card Footer & Action Buttons - DESIGN.md §9 */
    .del-card-footer {
        padding: 20px 28px;
        background: var(--del-bg-surface-2);
        border-top: 1px solid var(--del-border-subtle);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }
    .del-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 44px;
        padding: 0 20px;
        font-size: 14px;
        font-weight: 600;
        border-radius: var(--del-radius-md);
        cursor: pointer;
        transition: all 150ms ease;
        text-decoration: none;
        border: none;
        user-select: none;
    }
    .del-btn--secondary {
        background: transparent;
        color: var(--del-text-secondary);
        border: 1px solid var(--del-border-default);
    }
    .del-btn--secondary:hover {
        background: rgba(255, 255, 255, 0.05);
        color: var(--del-text-primary);
        border-color: var(--del-text-tertiary);
    }
    .del-btn--danger {
        background: var(--del-danger);
        color: #FFFFFF;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.35);
    }
    .del-btn--danger:hover {
        background: var(--del-danger-hover);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.45);
        transform: translateY(-1px);
    }
    .del-btn--danger:active {
        transform: scale(0.985);
    }
</style>
@endsection

@section('content')
<div class="page-content">
  <div class="container-fluid">

    <div class="del-page-wrapper">

        {{-- Top Navigation & Breadcrumb --}}
        <div class="del-top-bar">
            <div class="del-breadcrumb">
                <a href="{{ $invoice->is_custom == 1 ? route('list_invoice_tokabe_custom') : route('list_invoice_tokabe') }}">
                    <i class="ri-arrow-left-line me-1"></i>
                    {{ $invoice->is_custom == 1 ? 'Daftar Invoice Custom' : 'Daftar Invoice Tokabe' }}
                </a>
                <span class="del-breadcrumb-separator">/</span>
                <span class="del-breadcrumb-current">Konfirmasi Hapus #{{ $invoice->nomor_invoice }}</span>
            </div>
            <div>
                <a href="{{ $invoice->is_custom == 1 ? route('list_invoice_tokabe_custom') : route('list_invoice_tokabe') }}" class="del-btn del-btn--secondary" style="height: 36px; padding: 0 14px; font-size: 13px;">
                    <i class="ri-close-line"></i> Tutup
                </a>
            </div>
        </div>

        {{-- Main Confirmation Card --}}
        <div class="del-card">

            {{-- Card Header --}}
            <div class="del-card-header">
                <div class="del-header-left">
                    <div class="del-header-icon">
                        <i class="ri-delete-bin-7-line"></i>
                    </div>
                    <div>
                        <h1 class="del-header-title">Konfirmasi Penghapusan Invoice Tokabe</h1>
                        <p class="del-header-subtitle">Tinjau seluruh rincian transaksi sebelum menghapus invoice secara permanen dari sistem.</p>
                    </div>
                </div>

                {{-- Status Badge --}}
                <div>
                    @if($invoice->status === 'Lunas')
                        <span class="del-badge del-badge--success">Lunas</span>
                    @elseif($invoice->status === 'Batal')
                        <span class="del-badge del-badge--danger">Dibatalkan</span>
                    @else
                        <span class="del-badge del-badge--warning">Belum Lunas</span>
                    @endif
                </div>
            </div>

            {{-- Card Body --}}
            <div class="del-card-body">

                {{-- Destructive Alert Banner --}}
                <div class="del-alert-box">
                    <i class="ri-error-warning-fill del-alert-icon"></i>
                    <div class="del-alert-content">
                        <h4>Peringatan Penting — Aksi Destruktif</h4>
                        <p>
                            Tindakan ini akan <strong>menghapus data invoice secara permanen</strong>.
                            @if($invoice->status !== 'Batal')
                                Stok barang dan material yang tercatat pada transaksi ini akan <strong>secara otomatis dikembalikan ke inventaris gudang</strong>.
                            @else
                                Karena invoice ini sebelumnya sudah berstatus Batal, stok tidak akan dikembalikan ulang.
                            @endif
                            Pastikan Anda telah memeriksa data ini dengan seksama.
                        </p>
                    </div>
                </div>

                {{-- Invoice Overview Info Grid --}}
                <div class="del-info-grid">
                    <div class="del-info-item">
                        <span class="del-info-label">Nomor Invoice</span>
                        <span class="del-info-value" style="font-family: var(--font-mono, monospace); color: var(--del-brand);">
                            #{{ $invoice->nomor_invoice }}
                        </span>
                        <span class="del-info-sub">{{ $invoice->is_custom == 1 ? 'Invoice Kustom' : 'Invoice Reguler' }}</span>
                    </div>

                    <div class="del-info-item">
                        <span class="del-info-label">Tanggal Penjualan</span>
                        <span class="del-info-value">
                            {{ \Carbon\Carbon::parse($invoice->tgl_penjualan)->isoFormat('DD MMMM YYYY') }}
                        </span>
                        <span class="del-info-sub">{{ \Carbon\Carbon::parse($invoice->tgl_penjualan)->format('H:i') }} WIB</span>
                    </div>

                    <div class="del-info-item">
                        <span class="del-info-label">Pelanggan / Customer</span>
                        <span class="del-info-value">{{ $invoice->customer ?: '-' }}</span>
                        <span class="del-info-sub">
                            {{ $invoice->perusahaan ? $invoice->perusahaan . ' • ' : '' }}{{ $invoice->no_telepon ?: 'Tidak ada no. telp' }}
                        </span>
                    </div>

                    <div class="del-info-item">
                        <span class="del-info-label">Metode Pembayaran</span>
                        <span class="del-info-value">{{ $invoice->jenis_pembayaran ?: 'Belum ditentukan' }}</span>
                        <span class="del-info-sub">Rekening: {{ $invoice->no_rek ?: '-' }}</span>
                    </div>
                </div>

                {{-- Rincian Item / Barang --}}
                <div class="del-items-section">
                    <div class="del-section-header">
                        <h3 class="del-section-title">
                            <i class="ri-shopping-cart-2-line" style="color:var(--del-brand);"></i>
                            Rincian Item Transaksi
                        </h3>
                        <span class="del-badge" style="background:var(--del-bg-surface-3);color:var(--del-text-secondary);border-color:var(--del-border-subtle);">
                            {{ count($penjualan_barang) }} Item
                        </span>
                    </div>

                    <div class="del-table-wrapper">
                        <table class="del-table">
                            <thead>
                                <tr>
                                    <th style="width: 44px; text-align: center;">No</th>
                                    <th>Nama Produk / Jasa</th>
                                    <th>Deskripsi Item</th>
                                    <th class="numeric" style="width: 80px;">Qty</th>
                                    <th class="numeric" style="width: 140px;">Harga Satuan</th>
                                    <th class="numeric" style="width: 150px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penjualan_barang as $index => $jual)
                                    @php
                                        $itemCollection = $barang[$index] ?? collect();
                                        $product = $itemCollection->first();
                                        $productName = $product->jenis_barang ?? $product->nama_item ?? 'Produk';
                                        $subtotalItem = $jual->jumlah_harga ?? ($jual->harga * $jual->qty);
                                    @endphp
                                    <tr>
                                        <td style="text-align: center; color: var(--del-text-tertiary);">{{ $loop->iteration }}</td>
                                        <td style="font-weight: 600;">{{ $productName }}</td>
                                        <td style="color: var(--del-text-secondary); font-size: 12px;">{{ $jual->deskripsi_item ?: '-' }}</td>
                                        <td class="numeric">{{ $jual->qty }} {{ $jual->satuan ?: '' }}</td>
                                        <td class="numeric">Rp {{ number_format($jual->harga, 0, ',', '.') }}</td>
                                        <td class="numeric" style="font-weight: 600; color: var(--del-text-primary);">
                                            Rp {{ number_format($subtotalItem, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: var(--del-text-tertiary); padding: 24px;">
                                            Tidak ada rincian item pada invoice ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Financial Breakdown Box --}}
                <div class="del-finance-card">
                    <div class="del-finance-row">
                        <span>Total Nilai Barang</span>
                        <span class="del-finance-value">Rp {{ number_format($invoice->total_harga, 0, ',', '.') }}</span>
                    </div>

                    @if(!empty($invoice->diskon) && $invoice->diskon > 0)
                        <div class="del-finance-row" style="color: var(--del-warning-text);">
                            <span>Diskon ({{ $invoice->diskon }}%)</span>
                            <span class="del-finance-value">- Rp {{ number_format($invoice->total_harga * $invoice->diskon / 100, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if(!empty($invoice->potongan) && $invoice->potongan > 0)
                        <div class="del-finance-row" style="color: var(--del-warning-text);">
                            <span>Potongan Langsung</span>
                            <span class="del-finance-value">- Rp {{ number_format($invoice->potongan, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if(!empty($invoice->ppn) && $invoice->ppn > 0)
                        <div class="del-finance-row">
                            <span>PPN ({{ $invoice->ppn }}%)</span>
                            <span class="del-finance-value">+ Rp {{ number_format($invoice->total_harga * $invoice->ppn / 100, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if(!empty($invoice->dp) && $invoice->dp > 0)
                        <div class="del-finance-row">
                            <span>Uang Muka (DP)</span>
                            <span class="del-finance-value">Rp {{ number_format($invoice->dp, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="del-finance-row del-finance-row--total">
                        <span>Total Tagihan Akhir</span>
                        <span class="del-finance-value del-finance-value--green">Rp {{ $invoice->formatted_total_pembayaran }}</span>
                    </div>

                    <div class="del-finance-row">
                        <span>Sisa Pembayaran</span>
                        <span class="del-finance-value {{ $invoice->sisa_pembayaran > 0 ? 'del-finance-value--red' : 'del-finance-value--green' }}">
                            Rp {{ $invoice->formatted_sisa_pembayaran }} {{ $invoice->sisa_pembayaran == 0 ? '(Lunas)' : '' }}
                        </span>
                    </div>
                </div>

            </div>

            {{-- Card Footer & Actions (DESIGN.md §9 & §15.3) --}}
            <div class="del-card-footer">
                <a href="{{ $invoice->is_custom == 1 ? route('list_invoice_tokabe_custom') : route('list_invoice_tokabe') }}" class="del-btn del-btn--secondary">
                    <i class="ri-arrow-left-line"></i> Batal & Kembali
                </a>

                <form action="{{ route('delete.invoiceTokabe', $invoice->id) }}" method="POST" id="form-confirm-delete" class="m-0 p-0">
                    @csrf
                    @method('delete')
                    <button type="button" id="btn-trigger-delete" class="del-btn del-btn--danger">
                        <i class="ri-delete-bin-line"></i> Hapus Invoice Sekarang
                    </button>
                </form>
            </div>

        </div>

    </div>

  </div>
</div>
@endsection

@section('plugins')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnDelete = document.getElementById('btn-trigger-delete');
        const formDelete = document.getElementById('form-confirm-delete');

        if (btnDelete && formDelete) {
            btnDelete.addEventListener('click', function(e) {
                e.preventDefault();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Hapus Invoice Ini?',
                        html: `<div style="text-align:left; font-size:13px; line-height:1.6; color:#CBD5E1;">
                                 Apakah Anda yakin ingin menghapus invoice <strong>#{{ $invoice->nomor_invoice }}</strong>?
                                 <br><br>
                                 <div style="background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.35); padding:10px 14px; border-radius:8px; color:#FCA5A5;">
                                   <strong>Dampak:</strong> Data invoice akan dihapus secara permanen, dan kuantitas stok barang terkait akan dikembalikan ke gudang inventaris.
                                 </div>
                               </div>`,
                        icon: 'warning',
                        iconColor: '#EF4444',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#334155',
                        confirmButtonText: '<i class="ri-delete-bin-line me-1"></i> Ya, Hapus Sekarang',
                        cancelButtonText: 'Batal',
                        background: '#12161D',
                        color: '#F1F5F9',
                        reverseButtons: true,
                        focusCancel: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formDelete.submit();
                        }
                    });
                } else {
                    if (confirm('Apakah Anda yakin ingin menghapus invoice #{{ $invoice->nomor_invoice }} secara permanen? Stok barang akan dikembalikan ke inventaris.')) {
                        formDelete.submit();
                    }
                }
            });
        }
    });
</script>
@endsection
