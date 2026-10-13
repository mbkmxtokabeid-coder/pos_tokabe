@extends('layout.template')

@section('head')
<style>
  /* Form & Card Styling (DESIGN.md §10 & §12) */
  .card-form-custom {
    background-color: #161B22 !important;
    border: 1px solid #282E37 !important;
    border-radius: 12px !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2) !important;
    margin-bottom: 24px;
    overflow: hidden;
  }
  .card-form-custom .card-header {
    background-color: #181E26 !important;
    border-bottom: 1px solid #232A35 !important;
    padding: 16px 20px !important;
  }
  .card-form-custom .card-title {
    font-size: 15px !important;
    font-weight: 600 !important;
    color: #F8F9FA !important;
  }
  .col-form-label {
    color: #CBD5E1 !important;
    font-size: 13.5px !important;
    font-weight: 500 !important;
  }
  .form-control, .form-select {
    background-color: #212529 !important;
    border: 1px solid #383E45 !important;
    border-radius: 8px !important;
    color: #F8F9FA !important;
    font-size: 13.5px !important;
    transition: border-color 150ms ease, box-shadow 150ms ease !important;
  }
  .form-control:focus, .form-select:focus {
    border-color: var(--brand, #6366F1) !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25) !important;
    background-color: #262B32 !important;
    color: #FFFFFF !important;
  }
  .form-control[readonly], .form-control:disabled {
    background-color: #1A1F26 !important;
    border-color: #2D333B !important;
    color: #94A3B8 !important;
  }
  .input-group-text {
    background-color: #1A1F26 !important;
    border: 1px solid #383E45 !important;
    color: #CBD5E1 !important;
    font-size: 13px !important;
  }
  #ppn-nominal-badge, #pph-nominal-badge {
    background-color: rgba(99, 102, 241, 0.12) !important;
    border: 1px solid rgba(99, 102, 241, 0.28) !important;
    color: #818CF8 !important;
    font-weight: 600 !important;
    font-size: 12.5px !important;
  }
  .text-helper {
    color: #94A3B8 !important;
    font-size: 12px !important;
    margin-top: 5px;
    display: inline-block;
  }
  .error-help-block {
    color: #F87171 !important;
    font-size: 12px !important;
    margin-top: 4px;
    display: block;
  }

  /* Detail Barang Table (DESIGN.md §13) */
  .table-barang {
    border-collapse: separate !important;
    border-spacing: 0 !important;
    margin-bottom: 0 !important;
    border: 1px solid #282E37 !important;
    border-radius: 8px !important;
    overflow: hidden;
  }
  .table-barang thead th {
    background-color: #181E26 !important;
    color: #94A3B8 !important;
    font-size: 11.5px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    border-bottom: 1px solid #282E37 !important;
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
    padding: 12px 10px !important;
  }
  .table-barang tbody td {
    padding: 8px 10px !important;
    vertical-align: middle !important;
    border-bottom: 1px solid #21262D !important;
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
  }
  .table-barang tbody tr:last-child td {
    border-bottom: none !important;
  }
  .table-barang .form-control-sm, .table-barang .form-select-sm {
    height: 36px !important;
    border-radius: 6px !important;
    font-size: 13px !important;
  }
  .btn-tambah-barang {
    background-color: var(--brand, #6366F1) !important;
    border: 1px solid transparent !important;
    color: #FFFFFF !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    font-size: 12.5px !important;
    height: 34px !important;
    padding: 0 14px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
    box-shadow: 0 2px 6px rgba(99, 102, 241, 0.3) !important;
    transition: all 120ms ease !important;
  }
  .btn-tambah-barang:hover {
    background-color: var(--brand-hover, #4F46E5) !important;
    transform: translateY(-1px);
    color: #FFFFFF !important;
  }
  .btn-hapus-item {
    background-color: rgba(239, 68, 68, 0.1) !important;
    border: 1px solid rgba(239, 68, 68, 0.25) !important;
    color: #F87171 !important;
    border-radius: 6px !important;
    width: 34px !important;
    height: 34px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
    transition: all 120ms ease !important;
  }
  .btn-hapus-item:hover:not(:disabled) {
    background-color: #EF4444 !important;
    border-color: #EF4444 !important;
    color: #FFFFFF !important;
  }
  .btn-hapus-item:disabled {
    opacity: 0.3 !important;
    cursor: not-allowed !important;
    background-color: transparent !important;
    border-color: #383E45 !important;
    color: #64748B !important;
  }

  /* Summary Box */
  .summary-card {
    background-color: #181D24 !important;
    border: 1px solid #282E37 !important;
    border-radius: 10px !important;
    overflow: hidden;
  }
  .summary-card table {
    margin-bottom: 0 !important;
  }
  .summary-card td {
    padding: 10px 16px !important;
    border-bottom: 1px solid #232A35 !important;
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
  }
  .summary-card tr:last-child td {
    border-bottom: none !important;
  }
  .summary-label {
    color: #94A3B8 !important;
    font-size: 13px !important;
  }
  .summary-value {
    color: #CBD5E1 !important;
    font-size: 13.5px !important;
    font-weight: 500 !important;
    font-variant-numeric: tabular-nums;
  }
  .summary-highlight {
    background-color: rgba(255, 255, 255, 0.02) !important;
  }
  .summary-highlight .summary-label {
    color: #FFFFFF !important;
    font-weight: 700 !important;
    font-size: 14px !important;
  }
  .summary-highlight .summary-value {
    color: #FFFFFF !important;
    font-weight: 700 !important;
    font-size: 15px !important;
  }

  /* Action Buttons (DESIGN.md §9) */
  .btn-submit-pembelian {
    background-color: var(--brand, #6366F1) !important;
    border: 1px solid transparent !important;
    color: #FFFFFF !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    border-radius: 8px !important;
    height: 40px !important;
    padding: 0 24px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.35) !important;
    transition: all 120ms ease !important;
  }
  .btn-submit-pembelian:hover {
    background-color: var(--brand-hover, #4F46E5) !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.45) !important;
    color: #FFFFFF !important;
  }
  .btn-cancel-pembelian {
    background-color: #212529 !important;
    border: 1px solid #383E45 !important;
    color: #CBD5E1 !important;
    font-weight: 500 !important;
    font-size: 14px !important;
    border-radius: 8px !important;
    height: 40px !important;
    padding: 0 20px !important;
    display: inline-flex !important;
    align-items: center !important;
    transition: all 120ms ease !important;
    text-decoration: none !important;
  }
  .btn-cancel-pembelian:hover {
    background-color: #2A3037 !important;
    border-color: #4A525D !important;
    color: #FFFFFF !important;
  }
</style>
@endsection

@section('content')
<div class="page-content">
  <div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 text-white fw-bold">Edit Pembelian: {{ $pembelian->nomor_inv }}</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="{{ route('pembelian.index') }}" class="text-muted">Vendor</a></li>
          @if ($vendor)
            <li class="breadcrumb-item"><a href="{{ route('pembelian.vendor.show', $vendor->id) }}" class="text-muted">{{ $vendor->nama_vendor }}</a></li>
          @endif
          <li class="breadcrumb-item active text-white">Edit Pembelian</li>
        </ol>
      </div>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #FCA5A5; border-radius: 10px;">
        <strong class="d-flex align-items-center gap-1.5"><i class="ri-error-warning-line fs-18"></i> Terjadi Kesalahan:</strong>
        <ul class="mb-0 mt-1.5 ps-3 fs-13">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Tutup"></button>
      </div>
    @endif

        <div class="alert d-flex align-items-center gap-2 mb-4 py-2.5 px-3" role="alert" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.28); border-radius: 10px; color: #FCD34D;">
      <i class="ri-shield-keyhole-line fs-18 flex-shrink-0" style="color: #F59E0B;"></i>
      <span class="fs-13"><strong class="text-white">Catatan:</strong> Setelah Anda menyimpan perubahan pada riwayat pembelian ini, data akan otomatis kembali terkunci.</span>
    </div>
    <form action="{{ route('pembelian.update', $pembelian->id) }}" method="POST" id="form-pembelian">
      @csrf
      @method('PUT')

      {{-- Informasi Pembelian --}}
      <div class="card card-form-custom">
        <div class="card-header">
          <h5 class="card-title mb-0">Informasi Pembelian</h5>
        </div>
        <div class="card-body p-4">
          <div class="row g-3">
            {{-- Kolom Kiri --}}
            <div class="col-lg-6">
              <div class="mb-3 row">
                <label class="col-lg-4 col-form-label">Pakai Anggaran <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select name="anggaran" class="form-select @error('anggaran') is-invalid @enderror" required>
                    <option value="">-- Pilih Anggaran --</option>
                    @foreach ($budgets as $b)
                      <option value="{{ $b->id }}" {{ old('anggaran', $pembelian->anggaran) == $b->id ? 'selected' : '' }}>
                        {{ $b->nama_budget }}
                        @if($b->tanggal) ({{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}) @endif
                      </option>
                    @endforeach
                  </select>
                  @error('anggaran')<span class="error-help-block">{{ $message }}</span>@enderror
                </div>
              </div>

              <div class="mb-3 row">
                <label class="col-lg-4 col-form-label">Nama Vendor</label>
                <div class="col-lg-8">
                  <input type="hidden" name="id_vendor" value="{{ $vendor ? $vendor->id : $pembelian->id_vendor }}">
                  <input type="text" class="form-control" value="{{ $vendor ? $vendor->nama_vendor : '-' }}" readonly>
                </div>
              </div>

              <div class="mb-3 row">
                <label class="col-lg-4 col-form-label">Nomor Invoice <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="nomor_inv"
                         class="form-control @error('nomor_inv') is-invalid @enderror"
                         placeholder="Contoh: INV-2026-001"
                         value="{{ old('nomor_inv', $pembelian->nomor_inv) }}" required>
                  @error('nomor_inv')<span class="error-help-block">{{ $message }}</span>@enderror
                </div>
              </div>

              {{-- Jenis Invoice --}}
              <div class="mb-3 row">
                <label class="col-lg-4 col-form-label">Jenis Invoice <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select name="jenis_invoice" id="jenis_invoice" class="form-select @error('jenis_invoice') is-invalid @enderror" required onchange="handleJenisInvoiceChange()">
                    <option value="Barang" {{ old('jenis_invoice', $pembelian->jenis_invoice ?? 'Barang') === 'Barang' ? 'selected' : '' }}>Barang</option>
                    <option value="Jasa" {{ old('jenis_invoice', $pembelian->jenis_invoice ?? '') === 'Jasa' ? 'selected' : '' }}>Jasa</option>
                    <option value="Barang dan Jasa" {{ old('jenis_invoice', $pembelian->jenis_invoice ?? '') === 'Barang dan Jasa' ? 'selected' : '' }}>Barang dan Jasa</option>
                    <option value="Tanpa Pajak" {{ old('jenis_invoice', $pembelian->jenis_invoice ?? '') === 'Tanpa Pajak' ? 'selected' : '' }}>Tanpa Pajak</option>
                  </select>
                  @error('jenis_invoice')<span class="error-help-block">{{ $message }}</span>@enderror
                </div>
              </div>

              {{-- PPN (Dinamis: Barang atau Barang dan Jasa) --}}
              <div class="mb-3 row" id="ppn-container">
                <label class="col-lg-4 col-form-label">PPN (%)</label>
                <div class="col-lg-8">
                  <div class="input-group">
                    <input type="number" name="ppn_persen" id="ppn_persen" class="form-control" value="{{ old('ppn_persen', $pembelian->ppn_persen ?? 11) }}" min="0" max="100" step="0.1" oninput="hitungRingkasan()">
                    <span class="input-group-text">%</span>
                    <span class="input-group-text fw-semibold" id="ppn-nominal-badge">Rp 0</span>
                  </div>
                  <small class="text-helper">Nominal PPN ditambahkan ke Total Pembayaran</small>
                </div>
              </div>

              {{-- PPh (Dinamis: Jasa atau Barang dan Jasa) --}}
              <div class="mb-3 row" id="pph-container" style="display: none;">
                <label class="col-lg-4 col-form-label">PPh (%)</label>
                <div class="col-lg-8">
                  <div class="input-group">
                    <input type="number" name="pph_persen" id="pph_persen" class="form-control" value="{{ old('pph_persen', $pembelian->pph_persen ?? 2) }}" min="0" max="100" step="0.1" oninput="hitungRingkasan()">
                    <span class="input-group-text">%</span>
                    <span class="input-group-text fw-semibold" id="pph-nominal-badge">Rp 0</span>
                  </div>
                  <small class="text-helper">Nominal PPh (Pajak Penghasilan potong jasa)</small>
                </div>
              </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="col-lg-6">
              <div class="mb-3 row">
                <label class="col-lg-4 col-form-label">Tanggal Pembelian <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <input type="date" name="tanggal"
                         class="form-control @error('tanggal') is-invalid @enderror"
                         value="{{ old('tanggal', optional($pembelian->tanggal)->format('Y-m-d')) }}" required>
                  @error('tanggal')<span class="error-help-block">{{ $message }}</span>@enderror
                </div>
              </div>

              <div class="mb-3 row">
                <label class="col-lg-4 col-form-label">Tanggal Jatuh Tempo</label>
                <div class="col-lg-8">
                  <input type="date" name="tanggal_jto"
                         class="form-control @error('tanggal_jto') is-invalid @enderror"
                         value="{{ old('tanggal_jto', optional($pembelian->effective_tanggal_jto)->format('Y-m-d')) }}">
                  @error('tanggal_jto')<span class="error-help-block">{{ $message }}</span>@enderror
                </div>
              </div>

              <div class="mb-3 row">
                <label class="col-lg-4 col-form-label">Status Pembelian <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select name="status" id="status_pembelian" class="form-select w-100 @error('status') is-invalid @enderror" required onchange="handleStatusChange()" style="width: 100% !important; min-width: 100% !important; display: block !important;">
                    <option value="Belum Lunas" {{ old('status', $pembelian->status) === 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="Lunas" {{ old('status', $pembelian->status) === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                  </select>
                  @error('status')<span class="error-help-block">{{ $message }}</span>@enderror
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Detail Barang --}}
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Rincian Barang / Jasa</h5>
          <button type="button" class="btn btn-tambah-barang" onclick="tambahItem()">
            <i class="ri-add-line fs-16"></i>Tambah Barang
          </button>
        </div>
        <div class="card-body p-4">
          <div class="table-responsive">
            <table class="table align-middle table-barang" id="item-table">
              <thead>
                <tr>
                  <th style="min-width: 250px;">Keterangan / Deskripsi Barang <span class="text-danger">*</span></th>
                  <th style="width: 170px;" class="text-end">Harga Satuan (Rp) <span class="text-danger">*</span></th>
                  <th style="width: 100px;" class="text-center">Qty <span class="text-danger">*</span></th>
                  <th style="width: 120px;">Satuan</th>
                  <th style="width: 170px;" class="text-end">Jumlah Harga (Rp)</th>
                  <th style="width: 70px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody id="item-body">
                @forelse($pembelian->barang as $index => $item)
                <tr class="item-row">
                  <td>
                    <input type="text" name="items[{{ $index }}][deskripsi]" class="form-control form-control-sm" value="{{ $item->deskripsi }}" placeholder="Deskripsi item barang" required>
                  </td>
                  <td>
                    <input type="text" inputmode="numeric" name="items[{{ $index }}][harga_barang]" class="form-control form-control-sm harga text-end" value="{{ number_format($item->harga_barang, 0, ',', '.') }}" placeholder="0" oninput="formatInputRibuan(this); hitungBaris(this)" required>
                  </td>
                  <td>
                    <input type="number" name="items[{{ $index }}][qty]" class="form-control form-control-sm qty text-center" value="{{ $item->qty }}" placeholder="1" min="1" step="1" oninput="hitungBaris(this)" required>
                  </td>
                  <td>
                    <select name="items[{{ $index }}][satuan]" class="form-select form-select-sm">
                      @foreach(["Pcs", "Set", "Und", "Blok", "Rim", "Lbr", "Ktk", "Unit", "Kg", "Lainnya"] as $s)
                        <option value="{{ $s }}" {{ ($item->satuan ?? 'Pcs') === $s ? 'selected' : '' }}>{{ $s }}</option>
                      @endforeach
                    </select>
                  </td>
                  <td>
                    <input type="text" class="form-control form-control-sm jumlah-harga text-end" placeholder="0" readonly value="{{ number_format($item->total ?? ($item->harga_barang * $item->qty), 0, ',', '.') }}">
                  </td>
                  <td class="text-center">
                    <button type="button" class="btn btn-hapus-item" onclick="hapusItem(this)" title="Hapus Baris">
                      <i class="ri-delete-bin-line fs-15"></i>
                    </button>
                  </td>
                </tr>
                @empty
                <tr class="item-row">
                  <td>
                    <input type="text" name="items[0][deskripsi]" class="form-control form-control-sm" placeholder="Deskripsi item barang" required>
                  </td>
                  <td>
                    <input type="text" inputmode="numeric" name="items[0][harga_barang]" class="form-control form-control-sm harga text-end" placeholder="0" value="0" oninput="formatInputRibuan(this); hitungBaris(this)" required>
                  </td>
                  <td>
                    <input type="number" name="items[0][qty]" class="form-control form-control-sm qty text-center" placeholder="1" min="1" step="1" value="1" oninput="hitungBaris(this)" required>
                  </td>
                  <td>
                    <select name="items[0][satuan]" class="form-select form-select-sm">
                      <option value="Pcs">Pcs</option>
                      <option value="Set">Set</option>
                      <option value="Kg">Kg</option>
                      <option value="Lainnya">Lainnya</option>
                    </select>
                  </td>
                  <td>
                    <input type="text" class="form-control form-control-sm jumlah-harga text-end" placeholder="0" readonly value="0">
                  </td>
                  <td class="text-center">
                    <button type="button" class="btn btn-hapus-item" onclick="hapusItem(this)" disabled title="Hapus Baris">
                      <i class="ri-delete-bin-line fs-15"></i>
                    </button>
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- Ringkasan Pembayaran & Input Terbayar di Bawah --}}
          <div class="row justify-content-end mt-4">
            <div class="col-lg-5 col-md-7">
              <div class="summary-card shadow-sm">
                <table class="table w-100" id="summary-table">
                  <tbody>
                    <tr>
                      <td class="summary-label" style="width: 45%;">Subtotal</td>
                      <td class="text-end summary-value fw-semibold" id="summary-subtotal">Rp 0</td>
                    </tr>
                    <tr id="summary-ppn-row">
                      <td class="summary-label">PPN</td>
                      <td class="text-end summary-value" id="summary-ppn">Rp 0</td>
                    </tr>
                    <tr id="summary-pph-row" style="display: none;">
                      <td class="summary-label">PPh</td>
                      <td class="text-end summary-value" id="summary-pph">Rp 0</td>
                    </tr>
                    <tr class="summary-highlight">
                      <td class="summary-label">Jumlah Total</td>
                      <td class="text-end summary-value" id="summary-total">Rp 0</td>
                    </tr>
                    <tr>
                      <td class="summary-label align-middle">Terbayar</td>
                      <td class="text-end">
                        <div class="input-group input-group-sm">
                          <span class="input-group-text">Rp</span>
                          <input type="text" inputmode="numeric" name="terbayar" id="terbayar"
                                 class="form-control form-control-sm text-end fw-semibold"
                                 value="{{ number_format(old('terbayar', $pembelian->terbayar ?? 0), 0, ',', '.') }}"
                                 placeholder="0"
                                 oninput="formatInputRibuan(this); handleTerbayarChange()">
                        </div>
                      </td>
                    </tr>
                    <tr id="summary-sisa-row">
                      <td class="summary-label fw-bold">Sisa Pembayaran</td>
                      <td class="text-end summary-value fw-bold fs-15" id="summary-sisa">Rp 0</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="d-flex gap-2 mt-4 pt-2">
            <button type="submit" class="btn btn-submit-pembelian">
              <i class="ri-save-line fs-16"></i>Simpan Pembelian
            </button>
            <a href="{{ $vendor ? route('pembelian.vendor.show', $vendor->id) : route('pembelian.index') }}" class="btn btn-cancel-pembelian">Batal</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('plugins')
<script>
  let itemIndex = {{ count($pembelian->barang) > 0 ? count($pembelian->barang) : 1 }};
  const satuanOptions = ["Pcs", "Set", "Und", "Blok", "Rim", "Lbr", "Ktk", "Unit", "Kg", "Lainnya"];

  function parseRibuan(val) {
    if (typeof val === 'number') return val;
    if (!val) return 0;
    let clean = val.toString().replace(/\D/g, '');
    return parseFloat(clean) || 0;
  }

  function formatRp(n) {
    if (isNaN(n) || n === null || n === undefined) return '0';
    return Math.round(n).toLocaleString('id-ID');
  }

  function formatInputRibuan(input) {
    let raw = input.value.replace(/\D/g, '');
    if (raw === '') {
      input.value = '';
      return 0;
    }
    let num = parseInt(raw, 10);
    let oldLen = input.value.length;
    let cursor = input.selectionStart || oldLen;
    let formatted = num.toLocaleString('id-ID');
    input.value = formatted;
    let diff = formatted.length - oldLen;
    let newCursor = Math.max(0, cursor + diff);
    try {
      input.setSelectionRange(newCursor, newCursor);
    } catch (e) {}
    return num;
  }

  function satuanSelect(name) {
    let opts = satuanOptions.map(function(s) {
      return '<option value="' + s + '">' + s + '</option>';
    }).join('');
    return '<select name="' + name + '" class="form-select form-select-sm">' + opts + '</select>';
  }

  function tambahItem() {
    const i = itemIndex++;
    const tbody = document.getElementById('item-body');
    const tr = document.createElement('tr');
    tr.className = 'item-row';

    tr.innerHTML = [
      '<td><input type="text" name="items[' + i + '][deskripsi]" class="form-control form-control-sm" placeholder="Deskripsi item barang" required></td>',
      '<td><input type="text" inputmode="numeric" name="items[' + i + '][harga_barang]" class="form-control form-control-sm harga text-end" placeholder="0" value="0" oninput="formatInputRibuan(this); hitungBaris(this)" required></td>',
      '<td><input type="number" name="items[' + i + '][qty]" class="form-control form-control-sm qty text-center" placeholder="1" min="1" step="1" value="1" oninput="hitungBaris(this)" required></td>',
      '<td>' + satuanSelect('items[' + i + '][satuan]') + '</td>',
      '<td><input type="text" class="form-control form-control-sm jumlah-harga text-end" placeholder="0" readonly value="0"></td>',
      '<td class="text-center"><button type="button" class="btn btn-hapus-item" onclick="hapusItem(this)" title="Hapus Baris"><i class="ri-delete-bin-line fs-15"></i></button></td>'
    ].join('');

    tbody.appendChild(tr);
    updateHapusBtn();
    hitungRingkasan();
  }

  function hapusItem(btn) {
    btn.closest('tr').remove();
    updateHapusBtn();
    hitungRingkasan();
  }

  function updateHapusBtn() {
    const rows = document.querySelectorAll('#item-body .item-row');
    rows.forEach(function(row) {
      const button = row.querySelector('.btn-hapus-item');
      if (button) {
        button.disabled = rows.length <= 1;
      }
    });
  }

  function hitungBaris(input) {
    const row = input.closest('tr');
    const harga = parseRibuan(row.querySelector('.harga').value);
    const qty = parseFloat(row.querySelector('.qty').value) || 0;
    const subtotal = Math.round(harga * qty);
    row.querySelector('.jumlah-harga').value = formatRp(subtotal);
    hitungRingkasan();
  }

  function getSubtotalHarga() {
    let subtotal = 0;
    document.querySelectorAll('#item-body .item-row').forEach(function(row) {
      const harga = parseRibuan(row.querySelector('.harga').value);
      const qty = parseFloat(row.querySelector('.qty').value) || 0;
      subtotal += harga * qty;
    });
    return Math.round(subtotal);
  }

  function handleJenisInvoiceChange() {
    const jenis = document.getElementById('jenis_invoice').value;
    const ppnContainer = document.getElementById('ppn-container');
    const pphContainer = document.getElementById('pph-container');
    const ppnRow = document.getElementById('summary-ppn-row');
    const pphRow = document.getElementById('summary-pph-row');

    if (jenis === 'Barang') {
      ppnContainer.style.display = 'flex';
      pphContainer.style.display = 'none';
      ppnRow.style.display = 'table-row';
      pphRow.style.display = 'none';
    } else if (jenis === 'Jasa') {
      ppnContainer.style.display = 'none';
      pphContainer.style.display = 'flex';
      ppnRow.style.display = 'none';
      pphRow.style.display = 'table-row';
    } else if (jenis === 'Barang dan Jasa') {
      ppnContainer.style.display = 'flex';
      pphContainer.style.display = 'flex';
      ppnRow.style.display = 'table-row';
      pphRow.style.display = 'table-row';
    } else {
      // Tanpa Pajak
      ppnContainer.style.display = 'none';
      pphContainer.style.display = 'none';
      ppnRow.style.display = 'none';
      pphRow.style.display = 'none';
    }
    hitungRingkasan();
  }

  function hitungRingkasan() {
    const subtotal = getSubtotalHarga();
    const jenis = document.getElementById('jenis_invoice').value;

    let ppnPersen = (jenis === 'Barang' || jenis === 'Barang dan Jasa')
      ? (parseFloat(document.getElementById('ppn_persen').value) || 0) : 0;
    let pphPersen = (jenis === 'Jasa' || jenis === 'Barang dan Jasa')
      ? (parseFloat(document.getElementById('pph_persen').value) || 0) : 0;

    let ppnNominal = Math.round(subtotal * ppnPersen / 100);
    let pphNominal = Math.round(subtotal * pphPersen / 100);
    let total = subtotal + ppnNominal;

    document.getElementById('ppn-nominal-badge').textContent = 'Rp ' + formatRp(ppnNominal);
    document.getElementById('pph-nominal-badge').textContent = 'Rp ' + formatRp(pphNominal);

    // Update status & terbayar
    const statusEl = document.getElementById('status_pembelian');
    const status = statusEl ? statusEl.value : 'Belum Lunas';
    const terbayarInput = document.getElementById('terbayar');

    if (status === 'Lunas') {
      if (terbayarInput) {
        terbayarInput.value = formatRp(total);
        terbayarInput.readOnly = true;
      }
    } else {
      if (terbayarInput) {
        terbayarInput.readOnly = false;
        let curVal = parseRibuan(terbayarInput.value);
        if (curVal > total) {
          terbayarInput.value = formatRp(total);
        }
      }
    }

    const terbayar = terbayarInput ? parseRibuan(terbayarInput.value) : 0;
    const sisa = Math.max(0, total - terbayar);

    // Update ringkasan tabel bawah
    document.getElementById('summary-subtotal').textContent = 'Rp ' + formatRp(subtotal);
    document.getElementById('summary-ppn').textContent = 'Rp ' + formatRp(ppnNominal);
    document.getElementById('summary-pph').textContent = 'Rp ' + formatRp(pphNominal);
    document.getElementById('summary-total').textContent = 'Rp ' + formatRp(total);
    const sisaEl = document.getElementById('summary-sisa');
    sisaEl.textContent = 'Rp ' + formatRp(sisa);
    sisaEl.style.color = sisa > 0 ? '#FB7185' : '#34D399';
  }

  function handleStatusChange() {
    const statusEl = document.getElementById('status_pembelian');
    const status = statusEl ? statusEl.value : 'Belum Lunas';
    const subtotal = getSubtotalHarga();
    const jenis = document.getElementById('jenis_invoice').value;
    let ppnPersen = (jenis === 'Barang' || jenis === 'Barang dan Jasa')
      ? (parseFloat(document.getElementById('ppn_persen').value) || 0) : 0;
    let ppnNominal = Math.round(subtotal * ppnPersen / 100);
    let total = subtotal + ppnNominal;

    const terbayarInput = document.getElementById('terbayar');

    if (status === 'Lunas') {
      if (terbayarInput) {
        terbayarInput.value = formatRp(total);
        terbayarInput.readOnly = true;
      }
    } else {
      if (terbayarInput) {
        terbayarInput.readOnly = false;
        terbayarInput.value = '0';
      }
    }
    hitungRingkasan();
  }

  function handleTerbayarChange() {
    const subtotal = getSubtotalHarga();
    const jenis = document.getElementById('jenis_invoice').value;
    let ppnPersen = (jenis === 'Barang' || jenis === 'Barang dan Jasa')
      ? (parseFloat(document.getElementById('ppn_persen').value) || 0) : 0;
    let ppnNominal = Math.round(subtotal * ppnPersen / 100);
    let total = subtotal + ppnNominal;

    const terbayarInput = document.getElementById('terbayar');
    let terbayar = terbayarInput ? parseRibuan(terbayarInput.value) : 0;

    if (terbayar > total) {
      terbayar = total;
      if (terbayarInput) terbayarInput.value = formatRp(total);
    }

    const sisa = Math.max(0, total - terbayar);
    const sisaEl = document.getElementById('summary-sisa');
    sisaEl.textContent = 'Rp ' + formatRp(sisa);
    sisaEl.style.color = sisa > 0 ? '#FB7185' : '#34D399';

    const statusEl = document.getElementById('status_pembelian');
    if (statusEl) {
      if (total > 0 && terbayar >= total) {
        statusEl.value = 'Lunas';
      } else {
        statusEl.value = 'Belum Lunas';
      }
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    handleJenisInvoiceChange();
    updateHapusBtn();
    document.querySelectorAll('.harga').forEach(function(input) {
      formatInputRibuan(input);
    });
    const terbayarInput = document.getElementById('terbayar');
    if (terbayarInput) {
      formatInputRibuan(terbayarInput);
    }
    hitungRingkasan();

    // Sebelum form submit, pastikan angka dikirim tanpa titik pemisah
    document.querySelectorAll('form').forEach(function(form) {
      form.addEventListener('submit', function() {
        document.querySelectorAll('.harga').forEach(function(el) {
          el.value = el.value.replace(/\./g, '');
        });
        const tb = document.getElementById('terbayar');
        if (tb) {
          tb.value = tb.value.replace(/\./g, '');
        }
      });
    });
  });
</script>
@endsection