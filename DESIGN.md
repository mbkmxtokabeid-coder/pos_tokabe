# DESIGN.md — UI Kit & Panduan Gaya Website Kasir (POS)

> Dokumen acuan tunggal untuk desainer dan developer. Semua komponen memakai **design token** (CSS variable) sehingga mode terang/gelap, tema, dan perubahan brand cukup diubah di satu tempat.

---

## Daftar Isi

1. [Prinsip Desain](#1-prinsip-desain)
2. [Design Tokens (Fondasi)](#2-design-tokens-fondasi)
3. [Warna](#3-warna)
4. [Mode Gelap](#4-mode-gelap)
5. [Tipografi](#5-tipografi)
6. [Spacing, Grid & Layout](#6-spacing-grid--layout)
7. [Elevasi, Radius & Border](#7-elevasi-radius--border)
8. [Ikon](#8-ikon)
9. [Button](#9-button)
10. [Form & Input](#10-form--input)
11. [Dropdown, Menu & Popover](#11-dropdown-menu--popover)
12. [Card & Komponen Produk](#12-card--komponen-produk)
13. [Tabel & Daftar Data](#13-tabel--daftar-data)
14. [Badge, Tag & Status](#14-badge-tag--status)
15. [Feedback: Alert, Toast, Modal, Drawer, Tooltip](#15-feedback-alert-toast-modal-drawer-tooltip)
16. [Navigasi](#16-navigasi)
17. [Chart & Visualisasi Data](#17-chart--visualisasi-data)
18. [Animasi & Motion](#18-animasi--motion)
19. [Komponen Khusus Kasir (POS)](#19-komponen-khusus-kasir-pos)
20. [Empty, Loading & Error State](#20-empty-loading--error-state)
21. [Responsif & Touch](#21-responsif--touch)
22. [Aksesibilitas](#22-aksesibilitas)
23. [Format Data (Rupiah, Tanggal, Angka)](#23-format-data-rupiah-tanggal-angka)
24. [Konten & Microcopy](#24-konten--microcopy)
25. [Struktur File & Penamaan](#25-struktur-file--penamaan)
26. [Checklist Review](#26-checklist-review)

---

## 1. Prinsip Desain

Aplikasi kasir dipakai berjam-jam, di bawah tekanan antrean, sering dengan layar sentuh. Maka:

| Prinsip | Artinya dalam praktik |
|---|---|
| **Cepat di atas segalanya** | Aksi utama (tambah item, bayar) maksimal 1–2 ketukan. Hindari modal bertingkat. |
| **Jelas, bukan cantik** | Angka, harga, dan total selalu paling menonjol. Dekorasi tidak boleh mengalahkan data. |
| **Tahan salah** | Aksi destruktif (hapus, void, refund) butuh konfirmasi dan bisa di-undo bila memungkinkan. |
| **Konsisten** | Satu pola untuk satu fungsi. Tombol bayar selalu hijau/primer, hapus selalu merah. |
| **Ramah sentuh** | Target ketuk minimal 44×44 px (POS: 48 px). Jarak antar tombol cukup. |
| **Tidak melelahkan mata** | Kontras nyaman, mode gelap tersedia, tidak ada animasi mengganggu. |
| **Aksesibel** | Kontras WCAG AA, fokus keyboard terlihat, warna bukan satu-satunya penanda. |

---

## 2. Design Tokens (Fondasi)

Semua nilai visual disimpan sebagai CSS variable di `:root`. **Dilarang** menulis hex/px "mentah" di komponen; selalu pakai token.

```css
:root {
  /* ---------- Font ---------- */
  --font-sans: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  --font-mono: "JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;

  /* ---------- Spacing (basis 4px) ---------- */
  --space-0: 0;
  --space-1: 4px;
  --space-2: 8px;
  --space-3: 12px;
  --space-4: 16px;
  --space-5: 20px;
  --space-6: 24px;
  --space-8: 32px;
  --space-10: 40px;
  --space-12: 48px;
  --space-16: 64px;

  /* ---------- Radius ---------- */
  --radius-xs: 4px;
  --radius-sm: 6px;
  --radius-md: 8px;
  --radius-lg: 12px;
  --radius-xl: 16px;
  --radius-full: 9999px;

  /* ---------- Z-index ---------- */
  --z-base: 0;
  --z-sticky: 100;
  --z-dropdown: 200;
  --z-drawer: 300;
  --z-modal: 400;
  --z-toast: 500;
  --z-tooltip: 600;

  /* ---------- Motion ---------- */
  --dur-instant: 80ms;
  --dur-fast: 120ms;
  --dur-base: 200ms;
  --dur-slow: 320ms;
  --ease-standard: cubic-bezier(0.2, 0, 0, 1);
  --ease-decelerate: cubic-bezier(0, 0, 0, 1);
  --ease-accelerate: cubic-bezier(0.3, 0, 1, 1);
  --ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);

  /* ---------- Ukuran kontrol ---------- */
  --control-sm: 32px;
  --control-md: 40px;
  --control-lg: 48px;   /* default untuk layar POS sentuh */
  --control-xl: 56px;   /* tombol Bayar / aksi utama */

  /* ---------- Breakpoint (referensi, tidak bisa dipakai di media query) ---------- */
  /* sm 640 | md 768 | lg 1024 | xl 1280 | 2xl 1536 */
}
```

---

## 3. Warna

### 3.1 Palet Brand & Netral

**Primer (Biru Indigo)** — identitas, tombol utama, link, fokus.

| Token | Hex | Pemakaian |
|---|---|---|
| `--primary-50` | `#EEF2FF` | Background tipis, hover item |
| `--primary-100` | `#E0E7FF` | Background terpilih |
| `--primary-200` | `#C7D2FE` | Border aksen |
| `--primary-300` | `#A5B4FC` | Dekoratif |
| `--primary-400` | `#818CF8` | Primer di mode gelap (teks/ikon) |
| `--primary-500` | `#6366F1` | Hover (terang) |
| **`--primary-600`** | **`#4F46E5`** | **Primer utama (tombol, link)** |
| `--primary-700` | `#4338CA` | Pressed / active |
| `--primary-800` | `#3730A3` | Teks di atas bg primer muda |
| `--primary-900` | `#312E81` | Aksen gelap |

**Netral (Slate)** — teks, border, background.

| Token | Hex | Pemakaian |
|---|---|---|
| `--gray-0` | `#FFFFFF` | Surface kartu (terang) |
| `--gray-50` | `#F8FAFC` | Background halaman (terang) |
| `--gray-100` | `#F1F5F9` | Background sekunder, hover |
| `--gray-200` | `#E2E8F0` | Border default |
| `--gray-300` | `#CBD5E1` | Border input, divider kuat |
| `--gray-400` | `#94A3B8` | Placeholder, ikon nonaktif |
| `--gray-500` | `#64748B` | Teks sekunder |
| `--gray-600` | `#475569` | Teks sekunder kuat |
| `--gray-700` | `#334155` | Border (gelap), teks kuat |
| `--gray-800` | `#1E293B` | Surface (gelap) |
| `--gray-900` | `#0F172A` | Teks utama (terang) / bg (gelap) |
| `--gray-950` | `#020617` | Background terdalam (gelap) |

### 3.2 Warna Semantik

| Peran | 50 (bg) | 500 | 600 (utama) | 700 (teks) | Dipakai untuk |
|---|---|---|---|---|---|
| **Success** | `#ECFDF5` | `#10B981` | `#059669` | `#047857` | Lunas, berhasil, stok aman, **tombol Bayar** |
| **Warning** | `#FFFBEB` | `#F59E0B` | `#D97706` | `#B45309` | Stok menipis, menunggu, peringatan |
| **Danger** | `#FEF2F2` | `#EF4444` | `#DC2626` | `#B91C1C` | Hapus, void, refund, stok habis, error |
| **Info** | `#EFF6FF` | `#3B82F6` | `#2563EB` | `#1D4ED8` | Informasi netral, tip, catatan |

### 3.3 Token Semantik (yang dipakai komponen)

Komponen **hanya** memakai token semantik di bawah, bukan palet mentah.

```css
:root,
[data-theme="light"] {
  /* Background */
  --bg-app:          var(--gray-50);
  --bg-surface:      var(--gray-0);
  --bg-surface-2:    var(--gray-100);   /* panel di dalam surface */
  --bg-hover:        var(--gray-100);
  --bg-active:       var(--gray-200);
  --bg-overlay:      rgb(15 23 42 / 0.5);
  --bg-inverse:      var(--gray-900);

  /* Teks */
  --text-primary:    var(--gray-900);
  --text-secondary:  var(--gray-600);
  --text-tertiary:   var(--gray-500);
  --text-disabled:   var(--gray-400);
  --text-inverse:    #FFFFFF;
  --text-link:       var(--primary-600);
  --text-on-primary: #FFFFFF;

  /* Border */
  --border-subtle:   var(--gray-200);
  --border-default:  var(--gray-300);
  --border-strong:   var(--gray-400);
  --border-focus:    var(--primary-600);

  /* Brand */
  --brand:           var(--primary-600);
  --brand-hover:     var(--primary-700);
  --brand-active:    var(--primary-800);
  --brand-subtle:    var(--primary-50);
  --brand-subtle-hover: var(--primary-100);

  /* Status */
  --success:         #059669;  --success-hover: #047857;  --success-subtle: #ECFDF5;  --success-text: #047857;  --success-border: #A7F3D0;
  --warning:         #D97706;  --warning-hover: #B45309;  --warning-subtle: #FFFBEB;  --warning-text: #92400E;  --warning-border: #FDE68A;
  --danger:          #DC2626;  --danger-hover:  #B91C1C;  --danger-subtle:  #FEF2F2;  --danger-text:  #B91C1C;  --danger-border:  #FECACA;
  --info:            #2563EB;  --info-hover:    #1D4ED8;  --info-subtle:    #EFF6FF;  --info-text:    #1D4ED8;  --info-border:    #BFDBFE;

  /* Fokus */
  --focus-ring: 0 0 0 3px rgb(79 70 229 / 0.35);
}
```

### 3.4 Aturan Pemakaian Warna

- **Proporsi 60-30-10**: 60% netral (background/surface), 30% netral gelap (teks/border), 10% aksen (primer + status).
- **Satu aksi primer per layar/area.** Jika semua tombol berwarna, tidak ada yang menonjol.
- **Warna punya makna tetap**: hijau = sukses/bayar, merah = hapus/bahaya, kuning = hati-hati, biru = info. Jangan dipakai dekoratif.
- **Jangan hanya warna.** Status harus disertai ikon atau teks (untuk buta warna): `● Lunas`, `▲ Stok menipis`, `✕ Habis`.
- **Kontras minimum (WCAG AA)**: teks normal ≥ 4.5:1, teks besar (≥ 18px / 14px bold) ≥ 3:1, komponen UI & ikon ≥ 3:1.
- **Teks di atas warna solid** selalu putih (`--text-on-primary`) kecuali kuning/warning solid → pakai teks gelap `--gray-900`.
- **Jangan** gunakan abu-abu murni (`#808080`); selalu pakai skala Slate agar nada konsisten.

### 3.5 Warna Kategori Produk (opsional)

Untuk chip/ubin kategori. Pakai latar muda + teks tua agar kontras aman.

| Kategori | Latar | Teks/Ikon |
|---|---|---|
| Makanan | `#FFF7ED` | `#C2410C` |
| Minuman | `#ECFEFF` | `#0E7490` |
| Snack | `#FEFCE8` | `#A16207` |
| Sembako | `#F0FDF4` | `#15803D` |
| Elektronik | `#F5F3FF` | `#6D28D9` |
| Lainnya | `#F1F5F9` | `#475569` |

---

## 4. Mode Gelap

### 4.1 Cara Kerja

Mode gelap diaktifkan dengan atribut `data-theme="dark"` pada `<html>`. Ada tiga pilihan pengguna: **Terang · Gelap · Ikuti Sistem** (default). Pilihan disimpan di `localStorage`.

```css
[data-theme="dark"] {
  color-scheme: dark;

  --bg-app:          var(--gray-950);   /* #020617 */
  --bg-surface:      var(--gray-900);   /* #0F172A */
  --bg-surface-2:    var(--gray-800);   /* #1E293B */
  --bg-hover:        #1E293B;
  --bg-active:       #334155;
  --bg-overlay:      rgb(0 0 0 / 0.65);
  --bg-inverse:      var(--gray-50);

  --text-primary:    #F1F5F9;
  --text-secondary:  #CBD5E1;
  --text-tertiary:   #94A3B8;
  --text-disabled:   #64748B;
  --text-inverse:    var(--gray-900);
  --text-link:       var(--primary-400);
  --text-on-primary: #FFFFFF;

  --border-subtle:   #1E293B;
  --border-default:  #334155;
  --border-strong:   #475569;
  --border-focus:    var(--primary-400);

  --brand:           var(--primary-500);
  --brand-hover:     var(--primary-400);
  --brand-active:    var(--primary-300);
  --brand-subtle:    rgb(99 102 241 / 0.15);
  --brand-subtle-hover: rgb(99 102 241 / 0.25);

  --success: #10B981; --success-hover: #34D399; --success-subtle: rgb(16 185 129 / 0.15); --success-text: #6EE7B7; --success-border: rgb(16 185 129 / 0.35);
  --warning: #F59E0B; --warning-hover: #FBBF24; --warning-subtle: rgb(245 158 11 / 0.15); --warning-text: #FCD34D; --warning-border: rgb(245 158 11 / 0.35);
  --danger:  #EF4444; --danger-hover:  #F87171; --danger-subtle:  rgb(239 68 68 / 0.15);  --danger-text:  #FCA5A5; --danger-border:  rgb(239 68 68 / 0.35);
  --info:    #3B82F6; --info-hover:    #60A5FA; --info-subtle:    rgb(59 130 246 / 0.15); --info-text:    #93C5FD; --info-border:    rgb(59 130 246 / 0.35);

  --focus-ring: 0 0 0 3px rgb(129 140 248 / 0.45);
}

/* Ikuti sistem bila pengguna belum memilih */
@media (prefers-color-scheme: dark) {
  :root:not([data-theme="light"]) { /* salin token dark di atas, atau gunakan JS untuk set data-theme */ }
}
```

### 4.2 Skrip Anti-Kedip (taruh di `<head>`, sebelum CSS)

```html
<script>
  (function () {
    try {
      var saved = localStorage.getItem("theme"); // "light" | "dark" | "system" | null
      var prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
      var theme = saved === "light" || saved === "dark" ? saved : (prefersDark ? "dark" : "light");
      document.documentElement.setAttribute("data-theme", theme);
    } catch (e) {}
  })();
</script>
```

### 4.3 Aturan Mode Gelap

1. **Jangan hitam pekat** untuk surface kartu. Pakai skala Slate (`#0F172A`, `#1E293B`) agar ada hierarki kedalaman.
2. **Elevasi = lebih terang**, bukan bayangan. Makin tinggi lapisan, makin terang surface-nya (`app` → `surface` → `surface-2` → popover).
3. **Kurangi saturasi** warna aksen (pakai level 400–500, bukan 600–700) agar tidak "menyilaukan".
4. **Teks utama bukan putih murni** (`#F1F5F9`) untuk mengurangi *halation*.
5. **Latar status** memakai alpha (`rgb(... / 0.15)`) bukan warna solid pastel.
6. **Border lebih penting** di gelap; bayangan nyaris tak terlihat.
7. **Gambar produk**: beri `border-radius` + border tipis, dan opsional `filter: brightness(.92)` agar tidak terlalu menyilaukan.
8. **Chart**: gunakan palet khusus dark (lihat bagian 17), grid lebih redup.
9. **Transisi tema** halus: `transition: background-color var(--dur-base), color var(--dur-base), border-color var(--dur-base);` — hanya pada saat toggle (tambahkan class sementara `theme-transition`), bukan global.
10. Uji **setiap** komponen di kedua mode sebelum rilis.

### 4.4 Komponen Toggle Tema

- Segmented control 3 opsi: ☀ Terang · ☾ Gelap · 🖥 Sistem.
- Letakkan di menu profil/pengaturan dan sebagai ikon cepat di topbar (siklus terang↔gelap).
- Ikon berganti dengan animasi rotate+fade 200 ms.

---

## 5. Tipografi

### 5.1 Font

| Peran | Font | Catatan |
|---|---|---|
| **UI utama** | **Inter** (400, 500, 600, 700) | Sangat terbaca untuk angka & layar kecil. |
| **Angka/kode** | **JetBrains Mono** (500) | Kode produk, SKU, nomor struk, barcode. |
| **Cadangan** | `system-ui` | Dipakai bila font gagal dimuat. |

Aktifkan angka sejajar (tabular) untuk semua harga, total, dan kolom angka:

```css
.num, .price, td.numeric {
  font-variant-numeric: tabular-nums;
  font-feature-settings: "tnum" 1, "cv11" 1;
}
```

Muat font dengan `font-display: swap` dan *preload* untuk bobot 400 & 600.

### 5.2 Skala Tipografi

| Token | Ukuran / Line-height | Bobot | Letter-spacing | Dipakai untuk |
|---|---|---|---|---|
| `display` | 48 / 56 | 700 | -0.02em | Angka besar dashboard |
| `h1` | 32 / 40 | 700 | -0.02em | Judul halaman |
| `h2` | 24 / 32 | 600 | -0.01em | Judul seksi |
| `h3` | 20 / 28 | 600 | 0 | Judul kartu/modal |
| `h4` | 16 / 24 | 600 | 0 | Sub-judul |
| `body-lg` | 16 / 24 | 400 | 0 | Teks isi utama |
| `body` | 14 / 20 | 400 | 0 | **Default UI** |
| `body-sm` | 13 / 18 | 400 | 0 | Teks bantu, tabel padat |
| `caption` | 12 / 16 | 500 | 0.01em | Label kecil, metadata |
| `overline` | 11 / 16 | 600 | 0.08em, UPPERCASE | Label seksi, header tabel |
| `price-sm` | 14 / 20 | 600, tabular | 0 | Harga di daftar |
| `price-md` | 18 / 24 | 700, tabular | 0 | Harga kartu produk |
| `total` | 32 / 40 | 700, tabular | -0.01em | **Total belanja** |
| `total-xl` | 48 / 56 | 700, tabular | -0.02em | Total di layar bayar |

```css
:root {
  --text-display: 700 48px/56px var(--font-sans);
  --text-h1: 700 32px/40px var(--font-sans);
  --text-h2: 600 24px/32px var(--font-sans);
  --text-h3: 600 20px/28px var(--font-sans);
  --text-h4: 600 16px/24px var(--font-sans);
  --text-body: 400 14px/20px var(--font-sans);
  --text-caption: 500 12px/16px var(--font-sans);
}
```

### 5.3 Aturan Tipografi

- Ukuran teks interaktif minimum **14 px** (POS sentuh: **16 px**).
- Panjang baris isi 45–75 karakter; teks bantu `max-width: 65ch`.
- Maksimal **2 bobot** dalam satu komponen (mis. 400 + 600).
- Nama produk panjang dipotong dengan ellipsis 2 baris: `-webkit-line-clamp: 2`. Tooltip menampilkan nama penuh.
- Jangan pakai `UPPERCASE` untuk kalimat; hanya `overline` pendek.
- Rata kiri untuk teks, **rata kanan untuk angka & mata uang** di tabel.
- Simbol mata uang `Rp` lebih kecil (0.75em) dan dengan spasi tipis dari angka pada tampilan total besar.

---

## 6. Spacing, Grid & Layout

### 6.1 Skala Spacing

Basis **4 px**. Gunakan hanya token `--space-*`.

| Pemakaian | Token |
|---|---|
| Jarak ikon ke teks | `--space-2` (8) |
| Padding dalam button/input | `--space-3` / `--space-4` |
| Gap antar elemen satu grup | `--space-2` – `--space-3` |
| Padding kartu | `--space-4` (POS) / `--space-6` (dashboard) |
| Gap antar kartu | `--space-4` |
| Jarak antar seksi | `--space-8` |
| Margin halaman | `--space-4` (mobile), `--space-6` (tablet), `--space-8` (desktop) |

### 6.2 Grid

- **12 kolom**, gutter 16 px (mobile) / 24 px (desktop).
- Lebar maksimum konten dashboard: **1440 px**; layar POS mengisi penuh (`100vw`).
- Gunakan CSS Grid + `gap`, hindari margin negatif.

### 6.3 Breakpoint

| Nama | Lebar | Perangkat tipikal | Perilaku |
|---|---|---|---|
| `xs` | < 640 | HP | 1 kolom, bottom nav |
| `sm` | ≥ 640 | HP landscape | 2 kolom produk |
| `md` | ≥ 768 | Tablet portrait | Sidebar collapsed (ikon) |
| `lg` | ≥ 1024 | **Tablet landscape / POS** | Katalog + keranjang berdampingan |
| `xl` | ≥ 1280 | Desktop | Sidebar penuh |
| `2xl` | ≥ 1536 | Monitor besar | Lebih banyak kolom grid |

### 6.4 Layout Aplikasi

**Shell dashboard (admin/laporan)**

```
┌──────────┬───────────────────────────────────────────┐
│          │ Topbar (64px): judul · cari · notif · akun │
│ Sidebar  ├───────────────────────────────────────────┤
│ 256px /  │                                           │
│ 72px     │   Konten (padding 24–32px)                │
│ (collapsed)                                          │
└──────────┴───────────────────────────────────────────┘
```

**Layar Kasir (POS) — 3 zona**

```
┌────────────────────────────────────────────────────────────┐
│ Topbar POS (56px): outlet · shift · kasir · online · tema  │
├───────────────────────────────────────┬────────────────────┤
│ [ 🔍 Cari / Scan barcode ]            │  KERANJANG         │
│ [Semua][Makanan][Minuman][Snack] ...  │  Pelanggan: Umum ▾ │
│ ┌────┐┌────┐┌────┐┌────┐┌────┐        │  ──────────────    │
│ │    ││    ││    ││    ││    │        │  Item 1   − 2 +    │
│ │ P1 ││ P2 ││ P3 ││ P4 ││ P5 │        │  Item 2   − 1 +    │
│ └────┘└────┘└────┘└────┘└────┘        │  ──────────────    │
│ (grid produk, scroll sendiri)         │  Subtotal  Rp …    │
│                                       │  Diskon    − Rp …  │
│            ≈ 65% lebar                │  Pajak     Rp …    │
│                                       │  TOTAL    Rp 125.000│
│                                       │  [  BAYAR  (F9)  ] │
│                                       │     ≈ 35% (min 360)│
└───────────────────────────────────────┴────────────────────┘
```

- Keranjang **sticky**, scroll hanya pada daftar item; ringkasan total & tombol Bayar selalu terlihat.
- Di mobile: keranjang menjadi **bottom sheet** dengan bar ringkas "3 item · Rp 125.000 · Lihat".

---

## 7. Elevasi, Radius & Border

### 7.1 Bayangan

```css
:root {
  --shadow-xs: 0 1px 2px rgb(15 23 42 / 0.06);
  --shadow-sm: 0 1px 3px rgb(15 23 42 / 0.10), 0 1px 2px rgb(15 23 42 / 0.06);
  --shadow-md: 0 4px 8px -2px rgb(15 23 42 / 0.10), 0 2px 4px -2px rgb(15 23 42 / 0.06);
  --shadow-lg: 0 12px 24px -6px rgb(15 23 42 / 0.14), 0 4px 8px -4px rgb(15 23 42 / 0.08);
  --shadow-xl: 0 24px 48px -12px rgb(15 23 42 / 0.25);
}
[data-theme="dark"] {
  --shadow-xs: 0 0 0 1px rgb(255 255 255 / 0.04);
  --shadow-sm: 0 1px 2px rgb(0 0 0 / 0.5);
  --shadow-md: 0 4px 12px rgb(0 0 0 / 0.5);
  --shadow-lg: 0 12px 28px rgb(0 0 0 / 0.55);
  --shadow-xl: 0 24px 48px rgb(0 0 0 / 0.65);
}
```

| Level | Token | Dipakai untuk |
|---|---|---|
| 0 | none + border | Kartu datar, tabel |
| 1 | `--shadow-xs/sm` | Kartu produk, input fokus |
| 2 | `--shadow-md` | Dropdown, popover, kartu hover |
| 3 | `--shadow-lg` | Drawer, toast |
| 4 | `--shadow-xl` | Modal, dialog |

### 7.2 Radius per Komponen

| Komponen | Radius |
|---|---|
| Badge, tag kecil | `--radius-xs` (4) / `--radius-full` untuk pill |
| Button, input, select | `--radius-md` (8) |
| Kartu, dropdown | `--radius-lg` (12) |
| Modal, bottom sheet | `--radius-xl` (16) |
| Avatar, switch, FAB | `--radius-full` |

### 7.3 Border

- Default `1px solid var(--border-subtle)` untuk kartu; `var(--border-default)` untuk input.
- Fokus: ganti border ke `--border-focus` **dan** tambahkan `box-shadow: var(--focus-ring)`.
- Divider dalam kartu: `1px`, `--border-subtle`, bukan `<hr>` tebal.

---

## 8. Ikon

### 8.1 Pustaka & Gaya

- **Pustaka utama: [Lucide](https://lucide.dev)** (outline, stroke konsisten, MIT). Alternatif: Phosphor (Regular).
- Gaya: **outline**, `stroke-width: 1.75` (UI) / `2` (ikon kecil 16 px), sudut membulat, tanpa campuran gaya (jangan mencampur solid & outline).
- Ikon solid hanya untuk **status aktif** (mis. bintang favorit terisi, item nav aktif).

### 8.2 Ukuran

| Token | Ukuran | Pemakaian |
|---|---|---|
| `icon-xs` | 14 | Di dalam badge |
| `icon-sm` | 16 | Di dalam button kecil, input, tabel |
| `icon-md` | 20 | **Default** — button, nav, menu |
| `icon-lg` | 24 | Header kartu, toolbar |
| `icon-xl` | 32 | Empty state kecil, ubin kategori |
| `icon-2xl` | 48–64 | Empty state, ilustrasi status |

```css
.icon { width: 20px; height: 20px; flex-shrink: 0; stroke: currentColor; fill: none; stroke-width: 1.75; stroke-linecap: round; stroke-linejoin: round; }
```

### 8.3 Aturan

- Warna ikon mengikuti `currentColor` — **jangan** hard-code.
- Ikon dekoratif: `aria-hidden="true"`. Ikon berdiri sendiri (button ikon) **wajib** `aria-label`.
- Jarak ikon-ke-teks 8 px; ikon di kiri teks untuk aksi, di kanan untuk dropdown/navigasi maju.
- Area ketuk button ikon minimal 40×40 (POS 48×48) meski ikon hanya 20 px.
- Satu konsep = satu ikon di seluruh aplikasi.

### 8.4 Peta Ikon Standar (Lucide)

| Konsep | Ikon | Konsep | Ikon |
|---|---|---|---|
| Dashboard | `layout-dashboard` | Kasir / POS | `shopping-cart` / `monitor-smartphone` |
| Produk | `package` | Kategori | `tags` / `layers` |
| Stok / Inventori | `boxes` / `warehouse` | Stok menipis | `triangle-alert` |
| Transaksi | `receipt` | Riwayat | `history` |
| Pelanggan | `users` | Pelanggan tunggal | `user` |
| Supplier | `truck` | Pembelian | `shopping-bag` |
| Laporan | `bar-chart-3` | Tren | `trending-up` / `trending-down` |
| Keuangan | `wallet` | Tunai | `banknote` |
| Kartu | `credit-card` | QRIS | `qr-code` |
| Transfer | `landmark` | E-wallet | `smartphone` |
| Diskon / Promo | `percent` / `ticket-percent` | Voucher | `ticket` |
| Cetak struk | `printer` | Unduh | `download` |
| Barcode | `scan-barcode` | Cari | `search` |
| Tambah | `plus` | Kurangi | `minus` |
| Hapus | `trash-2` | Edit | `pencil` |
| Simpan | `save` / `check` | Batal / Tutup | `x` |
| Filter | `sliders-horizontal` | Urutkan | `arrow-up-down` |
| Kalender | `calendar` | Jam / Shift | `clock` |
| Pengaturan | `settings` | Notifikasi | `bell` |
| Profil | `circle-user` | Keluar | `log-out` |
| Info | `info` | Berhasil | `circle-check` |
| Peringatan | `triangle-alert` | Error | `circle-x` / `octagon-alert` |
| Bantuan | `circle-help` | Online / Offline | `wifi` / `wifi-off` |
| Tema terang / gelap | `sun` / `moon` | Menu | `menu` |
| Chevron | `chevron-down/up/left/right` | Panah | `arrow-left/right` |
| Tahan / Hold | `pause-circle` | Refund | `undo-2` |
| Void | `ban` | Meja / Dine-in | `armchair` |
| Outlet / Toko | `store` | Sinkronisasi | `refresh-cw` |

---

## 9. Button

### 9.1 Anatomi

```
[ ikon-kiri ] Label [ ikon-kanan ]
  padding-x   gap 8   padding-x
```

### 9.2 Varian

| Varian | Kapan dipakai | Tampilan |
|---|---|---|
| **Primary** | Aksi utama (maks. 1 per area): Simpan, Lanjut | Bg `--brand`, teks putih |
| **Success** | **Bayar**, Selesaikan transaksi, Konfirmasi lunas | Bg `--success`, teks putih |
| **Secondary** | Aksi pendukung: Batal, Kembali | Bg `--bg-surface`, border default, teks primer |
| **Tonal / Soft** | Aksi sekunder penting di area padat | Bg `--brand-subtle`, teks brand |
| **Ghost** | Aksi tersier, toolbar, tabel | Transparan, hover `--bg-hover` |
| **Outline-Brand** | Alternatif secondary beraksen | Border brand, teks brand |
| **Danger** | Hapus, Void, Refund (destruktif) | Bg `--danger`, teks putih |
| **Danger-Ghost** | Hapus di baris tabel / item keranjang | Transparan, teks danger, hover `--danger-subtle` |
| **Warning** | Aksi hati-hati (mis. Override harga) | Bg `--warning`, teks gelap |
| **Link** | Navigasi inline | Teks `--text-link`, underline saat hover |

### 9.3 Ukuran

| Ukuran | Tinggi | Padding-x | Font | Ikon | Dipakai |
|---|---|---|---|---|---|
| `sm` | 32 | 12 | 13/500 | 16 | Tabel, filter, toolbar |
| `md` | 40 | 16 | 14/500 | 20 | Dashboard (default desktop) |
| `lg` | 48 | 20 | 16/600 | 20 | **POS sentuh (default)** |
| `xl` | 56 | 24 | 18/700 | 24 | **Bayar / aksi utama POS** |
| `icon-sm` | 32×32 | – | – | 16 | Ikon tabel |
| `icon-md` | 40×40 | – | – | 20 | Toolbar |
| `icon-lg` | 48×48 | – | – | 24 | POS |
| `block` | 100% lebar | – | – | – | Tombol Bayar, form mobile |

### 9.4 State

| State | Perubahan |
|---|---|
| **Default** | Warna dasar |
| **Hover** | Warna `*-hover`, `transform: translateY(-1px)` + `shadow-sm` (hanya desktop/pointer fine) |
| **Active/Pressed** | Warna `*-active`, `transform: scale(0.98)`, tanpa shadow — durasi 80 ms |
| **Focus-visible** | `outline: none; box-shadow: var(--focus-ring)` (offset 2 px dengan ring surface) |
| **Disabled** | Opacity 0.5, `cursor: not-allowed`, tanpa hover/animasi, `aria-disabled` |
| **Loading** | Spinner 16–20 px menggantikan ikon, label tetap, lebar **tidak berubah**, `aria-busy="true"`, klik diblokir |
| **Success (transien)** | Ikon ✓ + label "Tersimpan" selama 1.5 s lalu kembali |

### 9.5 CSS Lengkap

```css
.btn {
  --btn-bg: var(--bg-surface);
  --btn-fg: var(--text-primary);
  --btn-border: var(--border-default);
  --btn-bg-hover: var(--bg-hover);
  --btn-bg-active: var(--bg-active);

  display: inline-flex; align-items: center; justify-content: center; gap: var(--space-2);
  height: var(--control-md); padding: 0 var(--space-4);
  font: 500 14px/1 var(--font-sans); white-space: nowrap;
  color: var(--btn-fg); background: var(--btn-bg);
  border: 1px solid var(--btn-border); border-radius: var(--radius-md);
  cursor: pointer; user-select: none; -webkit-tap-highlight-color: transparent;
  transition: background-color var(--dur-fast) var(--ease-standard),
              border-color var(--dur-fast) var(--ease-standard),
              box-shadow var(--dur-fast) var(--ease-standard),
              transform var(--dur-instant) var(--ease-standard);
}
.btn:hover:not(:disabled):not([aria-disabled="true"]) { background: var(--btn-bg-hover); }
.btn:active:not(:disabled) { background: var(--btn-bg-active); transform: scale(0.98); }
.btn:focus-visible { outline: none; box-shadow: var(--focus-ring); }
.btn:disabled, .btn[aria-disabled="true"] { opacity: .5; cursor: not-allowed; }

/* Varian */
.btn--primary { --btn-bg: var(--brand); --btn-bg-hover: var(--brand-hover); --btn-bg-active: var(--brand-active); --btn-fg: var(--text-on-primary); --btn-border: transparent; }
.btn--success { --btn-bg: var(--success); --btn-bg-hover: var(--success-hover); --btn-bg-active: var(--success-hover); --btn-fg: #fff; --btn-border: transparent; }
.btn--danger  { --btn-bg: var(--danger);  --btn-bg-hover: var(--danger-hover);  --btn-bg-active: var(--danger-hover);  --btn-fg: #fff; --btn-border: transparent; }
.btn--warning { --btn-bg: var(--warning); --btn-bg-hover: var(--warning-hover); --btn-bg-active: var(--warning-hover); --btn-fg: #111827; --btn-border: transparent; }
.btn--tonal   { --btn-bg: var(--brand-subtle); --btn-bg-hover: var(--brand-subtle-hover); --btn-fg: var(--brand); --btn-border: transparent; }
.btn--ghost   { --btn-bg: transparent; --btn-border: transparent; --btn-fg: var(--text-secondary); }
.btn--danger-ghost { --btn-bg: transparent; --btn-border: transparent; --btn-fg: var(--danger-text); --btn-bg-hover: var(--danger-subtle); }
.btn--outline-brand { --btn-bg: transparent; --btn-border: var(--brand); --btn-fg: var(--brand); --btn-bg-hover: var(--brand-subtle); }
.btn--link { --btn-bg: transparent; --btn-border: transparent; --btn-fg: var(--text-link); height: auto; padding: 0; }
.btn--link:hover { text-decoration: underline; background: transparent; }

/* Ukuran */
.btn--sm { height: var(--control-sm); padding: 0 var(--space-3); font-size: 13px; }
.btn--lg { height: var(--control-lg); padding: 0 var(--space-5); font-size: 16px; font-weight: 600; }
.btn--xl { height: var(--control-xl); padding: 0 var(--space-6); font-size: 18px; font-weight: 700; border-radius: var(--radius-lg); }
.btn--block { width: 100%; }
.btn--icon { width: var(--control-md); padding: 0; }
.btn--icon.btn--lg { width: var(--control-lg); }
.btn--icon.btn--sm { width: var(--control-sm); }

/* Loading */
.btn[aria-busy="true"] { position: relative; color: transparent; pointer-events: none; }
.btn[aria-busy="true"]::after {
  content: ""; position: absolute; width: 18px; height: 18px; border-radius: 50%;
  border: 2px solid rgb(255 255 255 / .4); border-top-color: #fff;
  animation: spin 700ms linear infinite;
}
.btn--secondary[aria-busy="true"]::after, .btn--ghost[aria-busy="true"]::after { border-color: var(--border-default); border-top-color: var(--brand); }
```

### 9.6 Pola Khusus

**Button Group (segmented)** — pilihan eksklusif (mis. Dine-in / Takeaway / Delivery).
- Border dibagi, radius hanya di ujung grup, item aktif memakai `--brand-subtle` + teks brand + border brand.
- Gunakan `role="radiogroup"` dengan `aria-checked`.

**Split Button** — aksi utama + dropdown aksi alternatif (mis. `Bayar ▾` → Tunai / QRIS / Kartu / Split bill). Pemisah vertikal 1 px putih 30% opasitas.

**Floating Action Button (FAB)** — hanya di mobile; 56×56, `--radius-full`, `--shadow-lg`, pojok kanan-bawah (16 px dari tepi, di atas bottom nav).

**Toggle Button** — status on/off (mis. Favorit): `aria-pressed`, ikon outline → solid.

**Quantity Stepper** — `[ − ] 2 [ + ]`, tombol 40–48 px, angka tengah `tabular-nums`, tekan-tahan = ulang cepat; nilai 0 memicu konfirmasi hapus atau tombol berubah menjadi 🗑.

**Chip / Pill Button** — filter kategori: tinggi 36–44, `--radius-full`; aktif = bg `--brand`, teks putih.

### 9.7 Aturan Button

- **Satu** Primary/Success per area. Sisanya Secondary/Ghost.
- Urutan di dialog (kiri→kanan): `Batal` (secondary) · `Aksi utama` (primary). Mobile: tumpuk, aksi utama di atas.
- Label berupa **kata kerja spesifik**: "Simpan Produk", "Bayar Rp 125.000" — bukan "OK"/"Submit".
- Button destruktif **tidak boleh** menjadi default-focus pada dialog konfirmasi.
- Cegah klik ganda: nonaktifkan saat request berjalan (debounce 300 ms).
- Jarak minimum antar tombol sentuh: **8 px** (aksi destruktif ≥ 16 px dari aksi umum).
- Jangan gunakan `<div>`/`<a>` sebagai tombol; pakai `<button>`; `<a>` hanya untuk navigasi.

---

## 10. Form & Input

### 10.1 Text Input

| Properti | Nilai |
|---|---|
| Tinggi | 40 (dashboard) / 48 (POS) |
| Padding-x | 12 |
| Radius | `--radius-md` |
| Border | `1px --border-default` |
| Bg | `--bg-surface` |
| Font | 14/400 (POS 16) — **16 px di mobile** agar iOS tidak auto-zoom |
| Placeholder | `--text-tertiary`, tidak menggantikan label |

State: **default → hover** (border `--border-strong`) **→ focus** (border `--brand` + `--focus-ring`) **→ filled → error** (border `--danger`, pesan merah + ikon) **→ success** (border `--success`, opsional) **→ disabled** (bg `--bg-surface-2`, teks `--text-disabled`) **→ read-only** (bg datar, tanpa ring).

```css
.input {
  width: 100%; height: var(--control-md); padding: 0 var(--space-3);
  font: 400 14px/20px var(--font-sans); color: var(--text-primary);
  background: var(--bg-surface); border: 1px solid var(--border-default); border-radius: var(--radius-md);
  transition: border-color var(--dur-fast), box-shadow var(--dur-fast);
}
.input::placeholder { color: var(--text-tertiary); }
.input:hover:not(:disabled) { border-color: var(--border-strong); }
.input:focus { outline: none; border-color: var(--border-focus); box-shadow: var(--focus-ring); }
.input[aria-invalid="true"] { border-color: var(--danger); }
.input[aria-invalid="true"]:focus { box-shadow: 0 0 0 3px rgb(239 68 68 / .3); }
.input:disabled { background: var(--bg-surface-2); color: var(--text-disabled); cursor: not-allowed; }
```

**Struktur field**

```
Label *                      ← 13/500, margin-bottom 6
[ input .................. ]
Teks bantu (opsional)        ← 12/400 --text-tertiary, margin-top 6
⚠ Pesan error                ← 12/500 --danger-text + ikon
```

- Label **di atas** input (bukan placeholder-only). Wajib: tanda `*` merah, atau tulis "(opsional)" pada yang tidak wajib.
- Validasi saat `blur` dan saat submit; hilangkan error segera setelah nilai valid.

### 10.2 Varian Input

| Varian | Detail |
|---|---|
| **Dengan ikon** | Ikon 16–20 px di kiri (`padding-left: 40`) / aksi di kanan (clear ×, tampilkan password) |
| **Prefix/Suffix** | Addon "Rp" di kiri, "%" atau "pcs" di kanan; border menyatu |
| **Input Rupiah** | Prefix `Rp`, rata kanan, `tabular-nums`, format ribuan otomatis saat mengetik (`125.000`), simpan sebagai integer |
| **Search** | Ikon `search` kiri, tombol clear kanan, `Esc` menghapus, debounce 250 ms |
| **Barcode scan** | Ikon `scan-barcode`, **auto-focus** selalu, `Enter` = tambah item, bunyi beep + flash hijau saat sukses/merah saat gagal |
| **Password** | Toggle mata `eye/eye-off`, indikator kekuatan |
| **PIN** | 4–6 kotak 56×56, `inputmode="numeric"`, auto-lanjut, mask titik |
| **Textarea** | Min 3 baris, `resize: vertical`, counter karakter bila ada batas |
| **Number** | `inputmode="decimal"`, stepper opsional, min/max |
| **Tanggal / Rentang** | Popover kalender, preset: Hari ini, Kemarin, 7 hari, Bulan ini, Kustom |

### 10.3 Checkbox, Radio, Switch

| Komponen | Spesifikasi |
|---|---|
| **Checkbox** | 20×20 (POS 24), radius 4, border `--border-strong`; checked: bg `--brand` + ikon centang putih; *indeterminate*: strip tengah. Area ketuk 44 px termasuk label |
| **Radio** | 20×20, bulat; terpilih: ring brand + titik tengah 8 px |
| **Switch** | 40×24 (POS 48×28), thumb 18/22; off: track `--gray-300`, on: track `--brand` (atau `--success` untuk aktif/nonaktif produk); thumb bergeser 200 ms `--ease-spring` |
| **Card Radio** | Kartu besar bisa diketuk (metode bayar): border 1→2 px brand + bg `--brand-subtle` + ikon centang pojok saat terpilih |

### 10.4 Select Native vs Custom

- Gunakan **native `<select>`** di mobile & untuk ≤ 7 opsi sederhana (lebih cepat, aksesibel).
- Gunakan **custom dropdown / combobox** (bagian 11) bila butuh pencarian, ikon, multi-pilih, atau gaya khusus.

### 10.5 Layout Form

- 1 kolom untuk form singkat; 2 kolom (≥ `lg`) untuk form panjang, field terkait dikelompokkan dengan judul seksi.
- Gap antar field **20 px**; antar seksi **32 px**.
- Footer form sticky: `Batal` + `Simpan`.
- Fokus otomatis ke field pertama; `Enter` men-submit; `Tab` urut logis.
- Simpan draf otomatis untuk form panjang (produk baru).

---

## 11. Dropdown, Menu & Popover

### 11.1 Jenis

| Jenis | Kegunaan |
|---|---|
| **Select Dropdown** | Pilih 1 nilai (kategori, satuan, pajak) |
| **Combobox / Autocomplete** | Cari + pilih (produk, pelanggan, supplier) |
| **Multi-select** | Pilih banyak, tampil sebagai chip |
| **Action Menu (kebab ⋮)** | Aksi per baris: Edit, Duplikat, Hapus |
| **User Menu** | Profil, tema, shift, keluar |
| **Split-button menu** | Alternatif aksi dari tombol utama |
| **Popover** | Konten bebas: filter, kalender, catatan cepat |
| **Context menu** | Klik kanan pada item keranjang (opsional desktop) |

### 11.2 Spesifikasi Panel

```css
.dropdown-panel {
  position: absolute; z-index: var(--z-dropdown);
  min-width: 100%; max-width: 360px; max-height: 320px; overflow-y: auto;
  padding: var(--space-1);
  background: var(--bg-surface); border: 1px solid var(--border-subtle);
  border-radius: var(--radius-lg); box-shadow: var(--shadow-md);
  transform-origin: top;
  animation: dropdown-in var(--dur-fast) var(--ease-decelerate);
}
.dropdown-item {
  display: flex; align-items: center; gap: var(--space-2);
  min-height: 36px; padding: 8px 10px; border-radius: var(--radius-sm);
  font: 400 14px/20px var(--font-sans); color: var(--text-primary); cursor: pointer;
}
.dropdown-item:hover, .dropdown-item[data-highlighted] { background: var(--bg-hover); }
.dropdown-item[aria-selected="true"] { background: var(--brand-subtle); color: var(--brand); font-weight: 500; }
.dropdown-item[aria-disabled="true"] { color: var(--text-disabled); cursor: not-allowed; }
.dropdown-item--danger { color: var(--danger-text); }
.dropdown-item--danger:hover { background: var(--danger-subtle); }
.dropdown-divider { height: 1px; margin: var(--space-1) 0; background: var(--border-subtle); }
.dropdown-label { padding: 6px 10px; font: 600 11px/16px var(--font-sans); letter-spacing: .08em; text-transform: uppercase; color: var(--text-tertiary); }

@keyframes dropdown-in { from { opacity: 0; transform: translateY(-4px) scale(.98); } to { opacity: 1; transform: none; } }
```

### 11.3 Perilaku

- Trigger: tinggi sama dengan input, ikon `chevron-down` di kanan **berputar 180°** saat terbuka (150 ms).
- Posisi: di bawah trigger, jarak 4 px; **balik ke atas** bila ruang bawah tidak cukup (collision detection).
- Tutup: klik di luar, `Esc`, memilih item (single-select), `Tab` keluar.
- **Keyboard**: `↓/↑` pindah sorotan, `Home/End`, `Enter/Space` memilih, huruf = *type-ahead*.
- Item terpilih diberi centang di kanan + `aria-selected`.
- Combobox: tampilkan maks 8 hasil, teks cocok **di-bold**, state "Tidak ditemukan" + aksi "Tambah baru '…'" bila relevan.
- Daftar panjang (> 100): virtualisasi.
- Mobile (< `md`): ganti menjadi **bottom sheet** dengan handle, bukan panel melayang.
- ARIA: `role="listbox"`/`option` (select), `role="menu"`/`menuitem` (aksi), `aria-expanded`, `aria-haspopup`, `aria-activedescendant`.

### 11.4 Item Menu Kaya

```
[ikon]  Label utama                    [⌘K / F2]
        Deskripsi kecil (opsional)
```
- Pintasan keyboard di kanan, `--text-tertiary`, font mono 12 px.
- Item destruktif dipisah divider dan diletakkan paling bawah.

---

## 12. Card & Komponen Produk

### 12.1 Card Dasar

```css
.card {
  background: var(--bg-surface); border: 1px solid var(--border-subtle);
  border-radius: var(--radius-lg); box-shadow: var(--shadow-xs);
}
.card__header { padding: var(--space-4) var(--space-5); border-bottom: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: space-between; }
.card__body   { padding: var(--space-5); }
.card__footer { padding: var(--space-3) var(--space-5); border-top: 1px solid var(--border-subtle); background: var(--bg-surface-2); border-radius: 0 0 var(--radius-lg) var(--radius-lg); }
.card--interactive { cursor: pointer; transition: box-shadow var(--dur-base), transform var(--dur-base), border-color var(--dur-base); }
.card--interactive:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); border-color: var(--border-default); }
.card--interactive:active { transform: scale(.985); }
```

### 12.2 Kartu Produk (Grid Katalog POS)

```
┌──────────────────────┐
│  ┌────────────────┐  │  ← gambar 1:1, radius 8, object-fit: cover
│  │                │  │     badge stok di pojok kiri-atas
│  │   [foto]       │  │     tombol favorit ♡ pojok kanan-atas
│  └────────────────┘  │
│  Kopi Susu Gula Aren │  ← 14/600, maks 2 baris
│  SKU-0012 · Minuman  │  ← 12/400 tertiary (opsional)
│  Rp 18.000      [+]  │  ← harga 18/700 tabular · tombol tambah 40px
└──────────────────────┘
```

- Lebar min 150 px (POS), grid: `repeat(auto-fill, minmax(160px, 1fr))`, gap 12–16.
- **Seluruh kartu dapat diketuk** untuk menambah 1 item; tombol `+` memberi petunjuk visual.
- Umpan balik ketuk: animasi *press* (scale .97) + *fly-to-cart* singkat atau badge keranjang berdenyut.
- State: **default · hover · pressed · terpilih (sudah di keranjang, badge qty) · stok habis** (gambar grayscale 100%, overlay "Habis", tidak bisa diketuk) **· stok menipis** (badge kuning).
- Tanpa foto: ubin berwarna kategori + inisial 2 huruf / ikon kategori.
- Varian tampilan: **Grid** (default) ↔ **Daftar** (toggle) untuk toko dengan ribuan SKU.

### 12.3 Item Keranjang

```
┌────────────────────────────────────────────┐
│ Kopi Susu Gula Aren               Rp 36.000│
│ Rp 18.000 × 2 · Less ice, extra shot       │
│ [ − ] 2 [ + ]       📝 Catatan    🗑        │
└────────────────────────────────────────────┘
```
- Tinggi min 72 px; geser kiri (swipe) → hapus (mobile); `Delete` pada baris terfokus (desktop).
- Item baru: animasi masuk slide-down + highlight latar `--brand-subtle` memudar 1 s.
- Diskon per item ditampilkan dengan harga asli dicoret.

### 12.4 Kartu Statistik (KPI)

```
Penjualan Hari Ini        [ikon dalam kotak tonal 40px]
Rp 4.250.000              ← display/total 32
▲ 12,4%  vs kemarin       ← hijau (naik) / merah (turun), disertai panah
[sparkline 7 hari]
```
- Perubahan positif/negatif **tidak hanya warna**: selalu ada panah ▲▼.

---

## 13. Tabel & Daftar Data

### 13.1 Spesifikasi

| Elemen | Aturan |
|---|---|
| Header | Sticky, bg `--bg-surface-2`, `overline` 11/600 UPPERCASE `--text-tertiary`, tinggi 40 |
| Baris | Tinggi 48 (nyaman) / 40 (padat) / 56 (sentuh); border bawah `--border-subtle` |
| Hover baris | Bg `--bg-hover` |
| Terpilih | Bg `--brand-subtle` + checkbox tercentang |
| Zebra | Tidak dipakai (cukup divider) |
| Angka | Rata kanan, `tabular-nums`; teks rata kiri |
| Aksi baris | Ikon kebab ⋮ atau 2 ikon ghost di kolom terakhir (rata kanan) |
| Kolom pertama | Tetap (sticky) pada scroll horizontal |
| Kepadatan | Toggle: Nyaman / Padat |

### 13.2 Fitur

- **Sortir**: klik header → ikon panah ↑↓, `aria-sort`.
- **Filter & pencarian** di atas tabel; filter aktif tampil sebagai chip yang bisa dihapus.
- **Pagination**: `‹ 1 2 3 … 10 ›` + pilihan "Tampilkan 10/25/50"; atau infinite scroll untuk riwayat.
- **Aksi massal**: bar muncul saat ≥ 1 baris dipilih ("3 dipilih · Hapus · Ekspor").
- **Ekspor**: CSV / Excel / PDF.
- **Total baris** (footer) tebal, bg `--bg-surface-2`.
- Mobile: tabel berubah menjadi **daftar kartu** (label: nilai bertumpuk).

```css
.table { width: 100%; border-collapse: separate; border-spacing: 0; font: 400 14px/20px var(--font-sans); }
.table th { position: sticky; top: 0; height: 40px; padding: 0 var(--space-4); text-align: left; font: 600 11px/16px var(--font-sans); letter-spacing: .08em; text-transform: uppercase; color: var(--text-tertiary); background: var(--bg-surface-2); border-bottom: 1px solid var(--border-subtle); }
.table td { height: 48px; padding: 0 var(--space-4); border-bottom: 1px solid var(--border-subtle); color: var(--text-primary); }
.table tbody tr:hover { background: var(--bg-hover); }
.table td.numeric, .table th.numeric { text-align: right; font-variant-numeric: tabular-nums; }
```

---

## 14. Badge, Tag & Status

### 14.1 Badge Status

Bentuk pill, tinggi 22–24, padding-x 8, font 12/500, **titik/ikon + teks**.

| Status | Warna | Teks contoh |
|---|---|---|
| Berhasil | success | ● Lunas · Selesai · Aktif · Stok aman |
| Menunggu | warning | ● Menunggu · Belum lunas · Ditahan |
| Gagal | danger | ● Gagal · Dibatalkan · Habis · Void |
| Info | info | ● Diproses · Baru · Preorder |
| Netral | gray | ● Draf · Nonaktif · Diarsipkan |

```css
.badge { display: inline-flex; align-items: center; gap: 6px; height: 24px; padding: 0 var(--space-2); font: 500 12px/1 var(--font-sans); border-radius: var(--radius-full); border: 1px solid transparent; }
.badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
.badge--success { color: var(--success-text); background: var(--success-subtle); border-color: var(--success-border); }
.badge--warning { color: var(--warning-text); background: var(--warning-subtle); border-color: var(--warning-border); }
.badge--danger  { color: var(--danger-text);  background: var(--danger-subtle);  border-color: var(--danger-border); }
.badge--info    { color: var(--info-text);    background: var(--info-subtle);    border-color: var(--info-border); }
.badge--neutral { color: var(--text-secondary); background: var(--bg-surface-2); border-color: var(--border-subtle); }
```

### 14.2 Lainnya

- **Counter badge** (notifikasi/keranjang): bulat 18–20 px, bg `--danger`, teks putih 11/700; >99 → "99+".
- **Tag/Chip**: dapat dihapus (×), radius 6, tinggi 28; untuk filter & multi-select.
- **Stok**: `> batas` hijau "Stok 48", `≤ batas` kuning "Menipis 5", `0` merah "Habis".
- **Metode bayar**: ikon kecil + nama (💵 Tunai, QRIS, Kartu).

---

## 15. Feedback: Alert, Toast, Modal, Drawer, Tooltip

### 15.1 Alert Inline

Bar horizontal: ikon + judul tebal + deskripsi + aksi opsional + tombol tutup. Latar `*-subtle`, border `*-border`, ikon & judul `*-text`, radius 12, padding 12–16. Dipakai untuk pesan yang tetap relevan di halaman (offline, langganan habis, validasi form).

### 15.2 Toast

| Properti | Nilai |
|---|---|
| Posisi | Kanan-atas (desktop), atas-tengah (mobile); jangan menutup tombol Bayar |
| Lebar | 320–420 |
| Durasi | Sukses 3 s · Info 4 s · Error **manual tutup** (atau 8 s) |
| Tumpukan | Maks 3, sisanya antre |
| Animasi | Masuk slide dari kanan 200 ms decelerate; keluar fade+slide 150 ms accelerate; pause saat hover |
| Isi | Ikon status + 1 kalimat + opsional aksi "Urungkan" |
| ARIA | `role="status"` (sukses/info), `role="alert"` (error) |

Contoh teks: "Produk ditambahkan ke keranjang · **Urungkan**".

### 15.3 Modal / Dialog

- Overlay `--bg-overlay` + blur 2–4 px; panel `--radius-xl`, `--shadow-xl`.
- Ukuran: `sm` 400 · `md` 560 · `lg` 720 · `xl` 960 · layar penuh (mobile).
- Struktur: Header (judul + ×) · Body (scroll internal) · Footer (aksi).
- Animasi: overlay fade 150 ms; panel `scale(.96) → 1` + fade 200 ms decelerate.
- **Focus trap**, `Esc` menutup, fokus kembali ke pemicu, scroll body dikunci.
- Klik overlay menutup **kecuali** form berisi perubahan belum disimpan.
- **Dialog Konfirmasi Destruktif**: ikon danger, judul jelas ("Hapus 'Kopi Susu'?"), konsekuensi ("Tindakan ini tidak dapat dibatalkan"), tombol `Batal` (fokus default) + `Hapus` (danger). Untuk aksi berisiko tinggi (tutup shift, hapus massal) minta ketik kata konfirmasi atau PIN supervisor.

### 15.4 Drawer / Side Sheet

- Dari kanan, lebar 400–560; untuk detail transaksi, edit cepat, filter lanjutan.
- Animasi `translateX(100%) → 0` 300 ms `--ease-decelerate`.
- Mobile: **bottom sheet** dengan handle drag 36×4, radius atas 16, bisa digeser untuk menutup.

### 15.5 Tooltip

- Tampil setelah hover 400 ms / fokus keyboard; hilang 0 ms. Bg `--bg-inverse`, teks `--text-inverse`, 12/500, padding 6×10, radius 6, maks 240 px.
- Hanya untuk info tambahan singkat atau label ikon; **jangan** menyimpan informasi penting hanya di tooltip (tidak ada di sentuh).
- `role="tooltip"` + `aria-describedby`.

### 15.6 Progress & Spinner

- **Spinner**: lingkaran 16/20/32 px, stroke 2–3, 700 ms linear infinite.
- **Progress bar**: tinggi 6–8, track `--bg-surface-2`, fill `--brand`, animasi lebar 300 ms; indeterminate = shimmer geser.
- **Skeleton** (bagian 20) untuk muat konten > 300 ms.

---

## 16. Navigasi

### 16.1 Sidebar

| Properti | Nilai |
|---|---|
| Lebar | 256 (penuh) / 72 (ikon saja) |
| Bg | `--bg-surface`, border kanan `--border-subtle` |
| Item | Tinggi 40, radius 8, ikon 20 + label 14/500, gap 12 |
| Hover | `--bg-hover` |
| Aktif | Bg `--brand-subtle`, teks & ikon `--brand`, bilah vertikal 3 px di kiri |
| Grup | Judul `overline`, jarak antar grup 24 |
| Collapse | Animasi lebar 250 ms; tooltip nama saat collapsed |
| Footer | Info outlet + kartu profil kasir |

Susunan menu: **Kasir (POS)** · Dashboard · Transaksi · Produk · Stok · Pelanggan · Pembelian · Laporan · Pengaturan.

### 16.2 Topbar

Tinggi 56 (POS) / 64 (dashboard): judul/breadcrumb · pencarian global (`⌘K`/`Ctrl K`) · indikator online/offline · sinkronisasi · notifikasi · toggle tema · avatar.

### 16.3 Tabs

- **Underline tabs** (halaman): tinggi 44, garis aktif 2 px brand bergeser (animasi indikator 200 ms).
- **Pill tabs** (filter/kategori): bg `--bg-surface-2`, tab aktif putih/`--bg-surface` + `--shadow-xs`.
- Keyboard: `←/→` pindah tab; `role="tablist"`.

### 16.4 Lainnya

- **Breadcrumb**: `Produk / Minuman / Kopi Susu`, pemisah `chevron-right`, item terakhir tebal non-link.
- **Pagination**: tombol 36×36, halaman aktif bg brand.
- **Bottom navigation** (mobile): 4–5 item, tinggi 64 + safe-area; ikon 24 + label 11; aktif = brand.
- **Stepper (wizard)**: lingkaran bernomor → ✓, garis penghubung; untuk setup toko, tutup shift.
- **Command Palette** (`Ctrl/⌘ K`): cari produk, halaman, aksi; modal tengah 560 px.

---

## 17. Chart & Visualisasi Data

### 17.1 Pustaka & Prinsip

- Rekomendasi: **Recharts / Chart.js / ECharts** (apa pun, ikuti token ini).
- Prinsip: **data-ink tinggi** — sedikit dekorasi, label langsung, sumbu bersih.
- Chart selalu ada **judul, periode, dan satuan** ("Penjualan 7 Hari · Rp").

### 17.2 Palet Chart

Urutan dipakai berurutan (aman untuk buta warna deuteranopia/protanopia — beda luminans).

| Seri | Terang | Gelap |
|---|---|---|
| 1 | `#4F46E5` (indigo) | `#818CF8` |
| 2 | `#0EA5E9` (sky) | `#38BDF8` |
| 3 | `#10B981` (emerald) | `#34D399` |
| 4 | `#F59E0B` (amber) | `#FBBF24` |
| 5 | `#EC4899` (pink) | `#F472B6` |
| 6 | `#8B5CF6` (violet) | `#A78BFA` |
| 7 | `#14B8A6` (teal) | `#2DD4BF` |
| 8 | `#94A3B8` (slate) → "Lainnya" | `#64748B` |

```css
:root { --chart-1:#4F46E5; --chart-2:#0EA5E9; --chart-3:#10B981; --chart-4:#F59E0B; --chart-5:#EC4899; --chart-6:#8B5CF6; --chart-7:#14B8A6; --chart-8:#94A3B8;
        --chart-grid: #E2E8F0; --chart-axis: #64748B; --chart-positive:#059669; --chart-negative:#DC2626; }
[data-theme="dark"] { --chart-1:#818CF8; --chart-2:#38BDF8; --chart-3:#34D399; --chart-4:#FBBF24; --chart-5:#F472B6; --chart-6:#A78BFA; --chart-7:#2DD4BF; --chart-8:#64748B;
        --chart-grid: #1E293B; --chart-axis: #94A3B8; --chart-positive:#34D399; --chart-negative:#F87171; }
```

**Palet berurutan** (heatmap jam ramai): `primary-50 → primary-600` (terang) / transparan → `primary-400` (gelap).

### 17.3 Pilih Jenis Chart

| Kebutuhan | Chart | Catatan |
|---|---|---|
| Tren penjualan dari waktu ke waktu | **Line / Area** | Area dengan gradasi 20% → 0% opasitas |
| Perbandingan antar kategori/produk | **Bar horizontal** (nama panjang) / Bar vertikal | Urutkan menurun; mulai dari 0 |
| Komposisi (metode bayar, kategori) | **Donut** (≤ 5 irisan) | Total di tengah; > 5 → bar |
| Produk terlaris (Top 10) | **Bar horizontal + angka** | Nilai di ujung bar |
| Jam/hari ramai | **Heatmap** | 7×24, skala berurutan |
| Target vs capaian | **Progress / Bullet / Gauge** | Satu angka besar + bar |
| Perbandingan periode | **Line ganda** (garis putus-putus periode lalu) | Legenda jelas |
| Tren mini di KPI | **Sparkline** | Tanpa sumbu, titik akhir ditandai |
| Laba/rugi | **Bar diverging** | Positif hijau di atas, negatif merah di bawah |

Hindari: pie > 5 irisan, 3D, sumbu Y terpotong pada bar, dua sumbu Y.

### 17.4 Spesifikasi Visual

| Elemen | Aturan |
|---|---|
| Garis | 2 px, `stroke-linecap: round`, kurva `monotone` (jangan melebihkan) |
| Titik data | Tersembunyi; muncul 4–5 px saat hover; titik terakhir selalu tampil |
| Bar | Radius atas 4, lebar fleksibel (maks 48), gap 4–8 |
| Donut | Ketebalan 16–24 px, gap antar irisan 2 px, radius dalam 70% |
| Grid | Horizontal saja, 1 px `--chart-grid`, putus-putus `3 3` |
| Sumbu | Tanpa garis sumbu; label 12/400 `--chart-axis`; angka disingkat (1,2 jt, 450 rb) |
| Label sumbu X | Maks ±7 label; sisanya dilewati; tanggal "12 Okt" |
| Legenda | Atas/kanan, titik 8 px + label; klik = tampil/sembunyi seri |
| Margin | 16–24 px agar label tidak terpotong |

### 17.5 Tooltip Chart

```
Rab, 7 Okt 2026
● Penjualan   Rp 4.250.000
● Transaksi   128
```
- Gaya sama dengan popover (surface, border, shadow-md, radius 8, padding 10×12), garis panduan vertikal tipis.
- Muncul 0 ms, pindah mulus 100 ms. Pada sentuh: ketuk-tahan.

### 17.6 Animasi & Interaksi Chart

- Muat: garis tergambar (stroke-dashoffset) 600–800 ms `--ease-decelerate`; bar tumbuh dari 0 dengan *stagger* 30 ms; donut berputar 600 ms. **Hanya saat pertama muncul**, bukan saat data refresh.
- Hover bar/irisan: opasitas seri lain 0.5, yang terpilih naik 1.05 / offset 4 px.
- Hormati `prefers-reduced-motion` → tanpa animasi.

### 17.7 Aksesibilitas Chart

- Setiap chart punya `role="img"` + `aria-label` ringkasan ("Penjualan 7 hari naik 12%, tertinggi Sabtu Rp 5,1 jt").
- Sediakan **tombol "Lihat sebagai tabel"** untuk data yang sama.
- Jangan membedakan seri hanya dengan warna: tambah pola/arah garis (solid vs putus) atau label langsung.

### 17.8 State Chart

Loading → skeleton bentuk chart · Kosong → "Belum ada data untuk periode ini" + ikon · Error → pesan + "Coba lagi".

---

## 18. Animasi & Motion

### 18.1 Filosofi

Animasi di aplikasi kasir **melayani kecepatan**: memberi umpan balik, menunjukkan hubungan, dan menenangkan transisi. **Bukan hiasan**. Jika mempercepat/menghambat kasir, hapus.

### 18.2 Durasi & Easing

| Jenis | Durasi | Easing |
|---|---|---|
| Umpan balik mikro (hover, press, ripple) | 80–120 ms | `ease-standard` |
| Toggle, checkbox, switch, dropdown | 120–200 ms | `ease-standard` / `ease-spring` |
| Masuk (modal, toast, popover) | 200–250 ms | `ease-decelerate` |
| Keluar | 120–180 ms | `ease-accelerate` (lebih cepat dari masuk) |
| Drawer, sidebar, halaman | 250–320 ms | `ease-standard` |
| Chart saat muat | 600–800 ms | `ease-decelerate` |
| **Batas atas apa pun** | **≤ 400 ms** (kecuali chart & skeleton) | – |

Hanya animasikan `transform` dan `opacity` (GPU-friendly). Hindari animasi `width/height/top/left/box-shadow` pada daftar besar.

### 18.3 Katalog Animasi

| Nama | Dipakai pada | Efek |
|---|---|---|
| **press** | Button, kartu produk | `scale(.97–.98)` 80 ms |
| **hover-lift** | Kartu interaktif | `translateY(-2px)` + shadow, 200 ms |
| **fade-in** | Muat halaman | opacity 0→1, 200 ms |
| **slide-up** | Toast mobile, bottom sheet | `translateY(16px)→0` + fade |
| **scale-in** | Modal, dropdown, popover | `scale(.96)→1` + fade |
| **item-add** | Baris keranjang baru | Slide-down 200 ms + highlight 1 s |
| **item-remove** | Hapus baris | Tinggi→0 + fade 200 ms |
| **cart-bump** | Badge keranjang | `scale 1→1.25→1` 300 ms `spring` |
| **number-tick** | Total berubah | Angka *cross-fade* / roll 200 ms (tanpa menggeser layout karena `tabular-nums`) |
| **success-check** | Pembayaran sukses | Lingkaran hijau + centang tergambar 500 ms + sedikit konfeti opsional |
| **shake** | Input/PIN salah | `translateX ±6px` 3× selama 300 ms |
| **pulse** | Indikator live/sinkronisasi | Opasitas 1→.4→1, 1.6 s infinite |
| **shimmer** | Skeleton | Gradien bergeser 1.4 s infinite |
| **spin** | Loading | Rotasi 700 ms linear |
| **chevron-rotate** | Dropdown, accordion | 180° 150 ms |
| **tab-indicator** | Tab underline | Geser + ubah lebar 200 ms |
| **theme-switch** | Toggle ☀/☾ | Rotate 90° + fade 200 ms |

### 18.4 Keyframes Inti

```css
@keyframes spin { to { transform: rotate(360deg); } }
@keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
@keyframes scale-in { from { opacity: 0; transform: scale(.96); } to { opacity: 1; transform: none; } }
@keyframes slide-up { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }
@keyframes slide-in-right { from { transform: translateX(100%); } to { transform: none; } }
@keyframes toast-in { from { opacity: 0; transform: translateX(24px); } to { opacity: 1; transform: none; } }
@keyframes shake { 0%,100%{transform:translateX(0)} 20%,60%{transform:translateX(-6px)} 40%,80%{transform:translateX(6px)} }
@keyframes bump { 0%{transform:scale(1)} 40%{transform:scale(1.25)} 100%{transform:scale(1)} }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
@keyframes shimmer { from { background-position: -200% 0; } to { background-position: 200% 0; } }
@keyframes highlight { from { background: var(--brand-subtle); } to { background: transparent; } }
@keyframes check-draw { from { stroke-dashoffset: 24; } to { stroke-dashoffset: 0; } }
```

### 18.5 Aturan

1. **Hormati preferensi pengguna**:
   ```css
   @media (prefers-reduced-motion: reduce) {
     *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; scroll-behavior: auto !important; }
   }
   ```
2. Jangan memblokir input saat animasi berjalan — transaksi tidak boleh menunggu animasi selesai.
3. Satu fokus gerak pada satu waktu; jangan animasi banyak elemen serentak (gunakan *stagger* 30–50 ms, maks 8 item).
4. Animasi berulang tak berujung hanya untuk indikator kecil (spinner, pulse).
5. Pada perangkat POS lemah, sediakan setelan "Mode Performa" yang mematikan animasi non-esensial.
6. Suara/haptic: beep singkat saat scan sukses, nada berbeda saat gagal; haptic ringan pada ketukan penting (bisa dimatikan).

---

## 19. Komponen Khusus Kasir (POS)

### 19.1 Panel Ringkasan & Total

```
Subtotal        Rp 120.000
Diskon (10%)  − Rp  12.000
Pajak PPN 11% + Rp  11.880
Biaya layanan   Rp       0
──────────────────────────
TOTAL           Rp 119.880   ← total 32/700
[        BAYAR  (F9)       ]  ← btn xl success block
```
- Baris sekunder 14/400 `--text-secondary`; angka rata kanan tabular; diskon `--success-text`.
- Total berubah → animasi *number-tick*.

### 19.2 Layar Pembayaran

1. **Total tagihan** raksasa (`total-xl`) di atas.
2. **Pilihan metode** sebagai *card radio* besar (min 96×80): Tunai · QRIS · Debit/Kredit · Transfer · E-wallet · Split.
3. **Tunai**:
   - Input "Uang diterima" + **tombol nominal cepat** (`Uang pas`, `50.000`, `100.000`, `200.000`) — chip 56 px.
   - **Kembalian** ditampilkan besar berwarna hijau (`total` ukuran) dan live saat mengetik.
   - Kurang bayar → teks merah "Kurang Rp 5.000", tombol Selesai nonaktif.
4. **QRIS**: QR 240×240 di panel putih (juga di mode gelap), hitung mundur masa berlaku, status polling "Menunggu pembayaran…" (pulse) → ✓.
5. **Split bill**: daftar pembayaran parsial + sisa tagihan.
6. Tombol **Selesaikan & Cetak** (success xl) + `Selesaikan tanpa struk`.

### 19.3 Numpad

Grid 3×4 (+ `C`, `⌫`, `00`, `000`), tombol 64×64 min, font 24/600 mono-tabular, gap 8, tombol `Enter` tinggi dua baris berwarna success. Dukung keyboard fisik. Tampilan display di atas rata kanan, auto-format ribuan.

### 19.4 Pelanggan, Diskon, Catatan

- Pemilih pelanggan: combobox + "Tambah pelanggan baru" inline; menampilkan poin/level.
- Diskon: toggle **% | Rp** + input; voucher kode dengan validasi live.
- Catatan pesanan: textarea ringkas + chip cepat ("Tanpa gula", "Pedas", "Bungkus").

### 19.5 Hold, Resume, Void, Refund

- **Tahan transaksi**: ikon `pause-circle`, daftar transaksi tertahan dengan waktu & total, badge jumlah.
- **Void/Refund**: butuh alasan (dropdown) + PIN supervisor; selalu danger + konfirmasi dua langkah.

### 19.6 Struk (Receipt)

- Pratinjau di modal bergaya kertas: font mono 12/16, lebar 58 mm / 80 mm, latar putih **selalu** (juga di mode gelap), garis putus-putus `- - -` pemisah, tepi bergerigi opsional.
- Isi: logo, nama toko, alamat, no. transaksi, tanggal, kasir, daftar item (qty × harga), subtotal, diskon, pajak, total, metode, kembalian, pesan terima kasih, QR/barcode.
- Aksi: Cetak · Unduh PDF · Kirim WhatsApp/Email.

### 19.7 Shift & Kas

- Banner shift aktif (jam buka, kasir, kas awal).
- **Buka shift**: input modal awal (Rupiah) · **Tutup shift**: hitung uang fisik, selisih (hijau = pas, merah = selisih), ringkasan per metode.

### 19.8 Status Koneksi & Mode Offline

| Status | Indikator |
|---|---|
| Online | Titik hijau + "Online" |
| Menyinkronkan | Ikon `refresh-cw` berputar + "Menyinkronkan 3 transaksi" |
| Offline | Banner kuning permanen: "Offline — transaksi disimpan & akan disinkronkan otomatis" |
| Gagal sync | Banner merah + tombol "Coba lagi" |

Aplikasi **tetap bisa transaksi saat offline**; tampilkan jumlah antrean sinkron.

### 19.9 Pintasan Keyboard

| Tombol | Aksi | Tombol | Aksi |
|---|---|---|---|
| `F2` | Fokus cari/scan | `F9` | Bayar |
| `F3` | Pilih pelanggan | `F10` | Tahan transaksi |
| `F4` | Diskon | `Ctrl+K` | Command palette |
| `Del` | Hapus item terpilih | `Esc` | Tutup / batal |
| `+` / `−` | Qty item terpilih | `Enter` | Konfirmasi |
| `?` | Daftar pintasan | `Ctrl+P` | Cetak ulang |

Tampilkan pintasan di tooltip/tombol (`Bayar  F9`).

---

## 20. Empty, Loading & Error State

### 20.1 Loading

- **Skeleton** untuk daftar/kartu/tabel/chart: bg `--bg-surface-2` + shimmer, bentuk meniru konten asli, radius sama.
- **Spinner** untuk aksi singkat (< 2 s) di dalam tombol.
- **Progress bar** untuk impor/ekspor/unggah.
- Tampilkan loading bila respons > 300 ms (hindari kedip).
- Optimistic UI: tambah item ke keranjang **instan**, rekonsiliasi di latar belakang.

```css
.skeleton { background: linear-gradient(90deg, var(--bg-surface-2) 25%, var(--bg-hover) 37%, var(--bg-surface-2) 63%); background-size: 400% 100%; animation: shimmer 1.4s ease infinite; border-radius: var(--radius-md); }
```

### 20.2 Empty State

Ikon/ilustrasi 48–64 px (warna `--text-tertiary`) · Judul 16/600 · Deskripsi 14 `--text-secondary` (maks 2 baris) · **1 CTA** jelas.

| Konteks | Teks |
|---|---|
| Keranjang kosong | "Keranjang masih kosong. Scan barcode atau pilih produk." |
| Produk belum ada | "Belum ada produk. Tambahkan produk pertama Anda." [Tambah Produk] |
| Hasil cari 0 | "Tidak ada hasil untuk '…'. Coba kata kunci lain." |
| Belum ada transaksi | "Belum ada transaksi hari ini." |

### 20.3 Error State

- **Inline field**: pesan di bawah input.
- **Level komponen**: kartu dengan ikon `circle-x`, pesan apa yang terjadi + apa yang bisa dilakukan + `Coba lagi`.
- **Level halaman** (404/500): ilustrasi sederhana, kode galat kecil, tombol "Kembali ke Kasir".
- **Offline**: lihat 19.8.
- Pesan: manusiawi, spesifik, tanpa menyalahkan, tanpa jargon teknis ("Printer tidak terhubung. Periksa kabel/Bluetooth lalu coba lagi.").

---

## 21. Responsif & Touch

### 21.1 Target Perangkat

| Perangkat | Layout |
|---|---|
| Tablet 10"–12" landscape (utama) | POS 2 panel |
| Desktop / laptop | POS 2 panel + sidebar dashboard |
| Tablet portrait | Katalog di atas, keranjang bottom sheet / drawer |
| HP | 1 kolom, bottom nav, keranjang sheet |

### 21.2 Aturan Sentuh

- Target ≥ **44×44** (POS **48×48**), jarak ≥ 8 px.
- Hapus hover-only: semua info penting terlihat tanpa hover.
- Hindari gestur tersembunyi sebagai satu-satunya cara (swipe-to-delete harus ada tombol alternatif).
- `touch-action: manipulation` agar tidak ada delay 300 ms; `user-select: none` pada tombol & kartu produk.
- Cegah zoom tak sengaja (font input ≥ 16 px di mobile).
- Dukung **safe area**: `padding-bottom: env(safe-area-inset-bottom)`.
- Orientasi: dukung rotasi tanpa kehilangan keranjang/state.

### 21.3 Pola Adaptif

| Komponen | Desktop | Mobile |
|---|---|---|
| Sidebar | Tetap | Drawer + bottom nav |
| Tabel | Tabel penuh | Daftar kartu |
| Modal | Tengah | Layar penuh / bottom sheet |
| Dropdown | Popover | Bottom sheet |
| Filter | Bar atas | Tombol "Filter" → sheet |
| Tab banyak | Semua terlihat | Scroll horizontal dengan fade tepi |

---

## 22. Aksesibilitas

Target: **WCAG 2.2 AA**.

- **Kontras**: lihat bagian 3.4; periksa di kedua mode.
- **Fokus**: `:focus-visible` selalu terlihat (ring 3 px); jangan `outline: none` tanpa pengganti.
- **Keyboard**: semua fungsi dapat dijalankan tanpa mouse; urutan tab logis; *skip link* "Lompat ke konten".
- **Semantik**: gunakan elemen HTML yang tepat (`button`, `nav`, `main`, `table`, `label`); ARIA hanya bila perlu.
- **Label**: setiap input punya `<label>`; button ikon punya `aria-label`.
- **Status dinamis**: toast/total berubah memakai `aria-live="polite"`; error `role="alert"`.
- **Warna bukan satu-satunya penanda** (ikon/teks/pola).
- **Ukuran teks** dapat diperbesar 200% tanpa kehilangan fungsi; gunakan `rem`.
- **Gerak**: hormati `prefers-reduced-motion`; tidak ada kedip > 3×/detik.
- **Tidak ada batas waktu tersembunyi** untuk aksi penting; beri peringatan sebelum sesi habis (jangan sampai keranjang hilang).
- **Bahasa**: `<html lang="id">`.
- **Screen reader**: uji dengan NVDA/VoiceOver pada alur utama (tambah item → bayar).

---

## 23. Format Data (Rupiah, Tanggal, Angka)

| Jenis | Format | Contoh |
|---|---|---|
| Mata uang | `Rp` + spasi + titik ribuan, tanpa desimal | `Rp 125.000` |
| Singkatan besar (chart/KPI) | rb, jt, M, T | `Rp 4,25 jt` |
| Negatif | tanda minus + warna danger | `− Rp 12.000` |
| Persentase | Koma desimal, 1 digit | `12,4%` |
| Angka | Titik ribuan, koma desimal | `1.250,5` |
| Tanggal | `d MMM yyyy` | `7 Okt 2026` |
| Tanggal panjang | `EEEE, d MMMM yyyy` | `Rabu, 7 Oktober 2026` |
| Jam | 24 jam | `14:35` |
| Relatif | < 24 jam: "5 menit lalu" | `2 jam lalu` |
| No. transaksi | Mono, prefiks + tanggal + urut | `INV-20261007-0042` |
| Satuan | Spasi + singkatan | `2 pcs`, `1,5 kg` |

```js
const rupiah = n => new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", maximumFractionDigits: 0 }).format(n);
const tanggal = d => new Intl.DateTimeFormat("id-ID", { day: "numeric", month: "short", year: "numeric" }).format(d);
```

- **Simpan uang sebagai integer** (rupiah/sen), jangan float.
- Zona waktu: simpan UTC, tampilkan sesuai outlet (WIB/WITA/WIT).

---

## 24. Konten & Microcopy

- **Bahasa Indonesia baku yang santai**: jelas, singkat, tidak kaku.
- **Kata kerja di tombol**: "Tambah Produk", "Simpan Perubahan", "Cetak Struk".
- **Konsisten istilah**: pilih satu — *Keranjang* (bukan "Cart"/"Pesanan" bergantian), *Bayar*, *Diskon*, *Pelanggan*, *Stok*.
- **Sapaan**: netral ("Anda") atau tidak menyapa sama sekali di UI operasional.
- **Pesan error** = apa yang salah + cara memperbaiki. Contoh: "Stok tidak cukup. Tersisa 3 pcs."
- **Konfirmasi** menyebut objeknya: "Hapus 'Kopi Susu'?" bukan "Hapus item ini?".
- **Angka & satuan** selalu bersama.
- Hindari tanda seru berlebihan & emoji di UI operasional (boleh pada empty state ringan).
- Kapitalisasi: **Sentence case** untuk label & tombol (kecuali nama sendiri).

---

## 25. Struktur File & Penamaan

```
src/
├─ styles/
│  ├─ tokens.css          ← semua variable (bagian 2, 3, 4)
│  ├─ base.css            ← reset, tipografi dasar, focus
│  ├─ motion.css          ← keyframes + reduced-motion
│  └─ utilities.css
├─ components/
│  ├─ Button/ Input/ Select/ Dropdown/ Modal/ Drawer/ Toast/ Tooltip/
│  ├─ Table/ Card/ Badge/ Tabs/ Chart/ Icon/ Skeleton/ EmptyState/
│  └─ pos/  ProductCard/ CartItem/ CartSummary/ Numpad/ PaymentMethod/ Receipt/ ShiftBanner/
└─ icons/                 ← Lucide (tree-shaken)
```

**Penamaan CSS**: BEM ringan — `.btn`, `.btn--primary`, `.btn__icon`; state memakai atribut ARIA/`data-*` (`[aria-busy]`, `[data-state="open"]`).

**Penamaan token**: `--{kategori}-{peran}-{varian}` → `--bg-surface-2`, `--text-secondary`, `--border-focus`, `--brand-hover`.

**Aturan kode**
- Dilarang hex/px mentah di komponen (kecuali 1 px border & nilai layout unik).
- Komponen tidak boleh tahu mode gelap; hanya token yang berubah.
- Tiap komponen punya: varian, ukuran, state, a11y, contoh (Storybook).

---

## 26. Checklist Review

Sebelum merge/rilis, setiap layar/komponen harus lolos:

**Visual**
- [ ] Hanya memakai token (warna, spasi, radius, shadow, font)
- [ ] Hierarki jelas: total/harga & aksi utama paling menonjol
- [ ] Tampil benar di **mode terang dan gelap**
- [ ] Hanya satu tombol primer/success per area

**Interaksi**
- [ ] Semua state ada: default, hover, focus, active, disabled, loading, error
- [ ] Target sentuh ≥ 44/48 px
- [ ] Aksi destruktif punya konfirmasi
- [ ] Animasi ≤ 400 ms, tidak memblokir input, menghormati reduced-motion

**Data**
- [ ] Format Rupiah/tanggal sesuai bagian 23; angka `tabular-nums` & rata kanan
- [ ] Loading (skeleton), empty, dan error state tersedia
- [ ] Chart: judul, satuan, tooltip, alternatif tabel, aman buta warna

**Aksesibilitas**
- [ ] Kontras AA, fokus terlihat, bisa dioperasikan keyboard
- [ ] Label/ARIA lengkap, status dinamis diumumkan
- [ ] Warna bukan satu-satunya penanda

**Responsif & Performa**
- [ ] Teruji di tablet landscape, tablet portrait, HP, desktop
- [ ] Berfungsi offline untuk alur transaksi
- [ ] Interaksi tambah-item terasa instan (< 100 ms)

---

*Versi 1.0 — Dokumen ini hidup. Perubahan token/komponen harus diperbarui di sini lebih dulu, lalu di kode.*
