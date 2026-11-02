-- =============================================================================
-- SQL SCRIPT: UPDATE DATABASE HOSTING TOKABE
-- Fitur: 
--   1. Penambahan Bank & Nomor Rekening pada Anggaran (Budget)
--   2. Modul Status Cicil & Tabel Riwayat Cicilan Pembelian (Pembelian Pembayaran)
--   3. Modul Beban Belanja & Kategori Beban Tokabe
--   4. Master Rekening & Approval Invoice (Kompatibilitas Penuh)
-- Tanggal: 02 November 2026
-- Kompatibel: MySQL 5.7+ / 8.0+ / MariaDB 10.2+ / phpMyAdmin cPanel Hosting
-- =============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

START TRANSACTION;

-- -----------------------------------------------------------------------------
-- 1. TABEL BUDGET (ANGGARAN): Tambah Kolom Bank dan Nomor Rekening
-- -----------------------------------------------------------------------------
ALTER TABLE `budget` 
  ADD COLUMN IF NOT EXISTS `bank` VARCHAR(100) NULL AFTER `anggaran`,
  ADD COLUMN IF NOT EXISTS `nomor_rekening` VARCHAR(100) NULL AFTER `bank`;


-- -----------------------------------------------------------------------------
-- 2. TABEL PEMBELIAN: Ubah Status 'Belum Lunas' Menjadi 'Cicil' & Tambah Kolom Pendukung
-- -----------------------------------------------------------------------------
-- Pastikan kolom penunjang approval dan jatuh tempo tersedia
ALTER TABLE `pembelian`
  ADD COLUMN IF NOT EXISTS `approval` VARCHAR(191) DEFAULT 'Lock' AFTER `token`,
  ADD COLUMN IF NOT EXISTS `approved_at` TIMESTAMP NULL AFTER `approval`,
  ADD COLUMN IF NOT EXISTS `tanggal_jto` DATE NULL AFTER `tanggal`,
  ADD COLUMN IF NOT EXISTS `tgl_lunas` DATE NULL AFTER `tgl_jto`;

-- Update semua data lama yang masih berstatus 'Belum Lunas' menjadi 'Cicil'
UPDATE `pembelian` 
SET `status` = 'Cicil' 
WHERE `status` = 'Belum Lunas';

-- Perbarui definisi kolom ENUM status (hanya 'Lunas' dan 'Cicil')
ALTER TABLE `pembelian` 
  MODIFY COLUMN `status` ENUM('Lunas', 'Cicil') NOT NULL DEFAULT 'Cicil';


-- -----------------------------------------------------------------------------
-- 3. TABEL PEMBELIAN PEMBAYARAN: Riwayat Pembayaran Cicilan (Maksimal 5x)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pembelian_pembayaran` (
  `id` CHAR(36) NOT NULL,
  `pembelian_id` CHAR(36) NOT NULL,
  `cicilan_ke` TINYINT(3) UNSIGNED NOT NULL,
  `tanggal_bayar` DATE NOT NULL,
  `jumlah_bayar` BIGINT(20) NOT NULL,
  `anggaran_id` BIGINT(20) UNSIGNED NOT NULL,
  `status_approval` ENUM('Pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'Approved',
  `catatan` VARCHAR(255) DEFAULT NULL,
  `user_id` BIGINT(20) UNSIGNED DEFAULT NULL,
  `approved_by` BIGINT(20) UNSIGNED DEFAULT NULL,
  `approved_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembelian_pembayaran_anggaran_id_foreign` (`anggaran_id`),
  KEY `pembelian_pembayaran_user_id_foreign` (`user_id`),
  KEY `pembelian_pembayaran_approved_by_foreign` (`approved_by`),
  KEY `pembelian_pembayaran_pembelian_id_status_approval_index` (`pembelian_id`, `status_approval`),
  KEY `pembelian_pembayaran_tanggal_bayar_index` (`tanggal_bayar`),
  CONSTRAINT `pembelian_pembayaran_anggaran_id_foreign` FOREIGN KEY (`anggaran_id`) REFERENCES `budget` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pembelian_pembayaran_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pembelian_pembayaran_pembelian_id_foreign` FOREIGN KEY (`pembelian_id`) REFERENCES `pembelian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pembelian_pembayaran_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migrasikan data uang muka (DP) dari pembelian cicil yang sudah berjalan ke riwayat pembayaran ke-1
INSERT INTO `pembelian_pembayaran` (
  `id`, `pembelian_id`, `cicilan_ke`, `tanggal_bayar`, `jumlah_bayar`, 
  `anggaran_id`, `status_approval`, `catatan`, `created_at`, `updated_at`
)
SELECT 
  UUID(),
  p.id,
  1,
  p.tanggal,
  p.terbayar,
  p.anggaran,
  'Approved',
  'Pembayaran Awal / DP (Migrasi Otomatis)',
  NOW(),
  NOW()
FROM `pembelian` p
WHERE p.status = 'Cicil' 
  AND p.terbayar > 0 
  AND p.id NOT IN (SELECT DISTINCT pembelian_id FROM `pembelian_pembayaran`);


-- -----------------------------------------------------------------------------
-- 4. TABEL REKENING: Rekening Bank Tokabe (Jika belum ada)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rekening` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_bank` VARCHAR(100) NOT NULL,
  `atas_nama` VARCHAR(150) NOT NULL,
  `nomor_rekening` VARCHAR(100) NOT NULL,
  `keterangan` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('Aktif', 'Non Aktif') NOT NULL DEFAULT 'Aktif',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Rekening Default BSI jika belum ada
INSERT INTO `rekening` (`id`, `nama_bank`, `atas_nama`, `nomor_rekening`, `keterangan`, `status`, `created_at`, `updated_at`)
SELECT 1, 'BSI', 'PT. Total Karya Berkah', '3557999999', 'Rekening Utama Pembayaran Invoice Tokabe', 'Aktif', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `rekening` WHERE `id` = 1);


-- -----------------------------------------------------------------------------
-- 5. MODUL BEBAN BELANJA: Kategori Beban & Beban Belanja Harian
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `kategori_beban` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kategori` VARCHAR(100) NOT NULL,
  `deskripsi` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Kategori Beban Standar jika belum ada
INSERT IGNORE INTO `kategori_beban` (`id`, `nama_kategori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Operasional & Utilitas', 'Tagihan listrik, air (PDAM), wifi/internet, sewa tempat toko', NOW(), NOW()),
(2, 'Perlengkapan & Packing', 'Lakban, bubble wrap, plastik packing, kertas nota dan ATK toko', NOW(), NOW()),
(3, 'Pemeliharaan Mesin & Alat', 'Servis mesin UV, maintenance mesin sablon/printer, sparepart', NOW(), NOW()),
(4, 'Gaji & Upah', 'Gaji karyawan, uang lembur, upah freelance / tukang', NOW(), NOW()),
(5, 'Konsumsi & Dapur', 'Air galon, kopi/teh, konsumsi lembur dan operasional harian', NOW(), NOW()),
(6, 'Pemasaran & Promosi', 'Biaya iklan FB/IG/TikTok Ads, brosur promosi, endorsement', NOW(), NOW()),
(7, 'Beban Lain-lain', 'Biaya administrasi bank, retribusi, dan pengeluaran umum lainnya', NOW(), NOW());

CREATE TABLE IF NOT EXISTS `beban_belanja` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomor_beban` VARCHAR(50) NOT NULL,
  `tanggal` DATE NOT NULL,
  `kategori_id` BIGINT(20) UNSIGNED DEFAULT NULL,
  `nama_beban` VARCHAR(255) NOT NULL,
  `nominal` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  `anggaran_id` BIGINT(20) UNSIGNED DEFAULT NULL,
  `rekening_id` BIGINT(20) UNSIGNED DEFAULT NULL,
  `penerima` VARCHAR(150) DEFAULT NULL,
  `bukti_struk` VARCHAR(255) DEFAULT NULL,
  `keterangan` TEXT DEFAULT NULL,
  `admin_id` BIGINT(20) UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `beban_belanja_nomor_beban_unique` (`nomor_beban`),
  KEY `beban_belanja_kategori_id_foreign` (`kategori_id`),
  KEY `beban_belanja_rekening_id_foreign` (`rekening_id`),
  KEY `beban_belanja_admin_id_foreign` (`admin_id`),
  KEY `beban_belanja_anggaran_id_foreign` (`anggaran_id`),
  KEY `beban_belanja_tanggal_kategori_id_index` (`tanggal`, `kategori_id`),
  KEY `beban_belanja_nomor_beban_index` (`nomor_beban`),
  CONSTRAINT `beban_belanja_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `beban_belanja_anggaran_id_foreign` FOREIGN KEY (`anggaran_id`) REFERENCES `budget` (`id`) ON DELETE SET NULL,
  CONSTRAINT `beban_belanja_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_beban` (`id`) ON DELETE SET NULL,
  CONSTRAINT `beban_belanja_rekening_id_foreign` FOREIGN KEY (`rekening_id`) REFERENCES `rekening` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pastikan kolom anggaran_id ada pada beban_belanja
ALTER TABLE `beban_belanja`
  ADD COLUMN IF NOT EXISTS `anggaran_id` BIGINT(20) UNSIGNED NULL AFTER `nominal`;


-- -----------------------------------------------------------------------------
-- 6. TABEL ITEMS & APPROVAL REQUEST FAKTUR PAJAK (Pencegah Error Jika Belum Ada)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `items` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_item` VARCHAR(191) NOT NULL,
  `jenis_pajak` ENUM('ppn', 'pph', 'ppn_pph', 'tanpa_pajak') NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `items_nama_item_unique` (`nama_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `penjualan_jasa_tokabe`
  ADD COLUMN IF NOT EXISTS `item_id` BIGINT(20) UNSIGNED NULL AFTER `barang_id`;

CREATE TABLE IF NOT EXISTS `invoice_approval_requests` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` BIGINT(20) UNSIGNED NOT NULL,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `tipe_request` VARCHAR(50) NOT NULL DEFAULT 'edit_faktur_pajak',
  `status` ENUM('Pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending',
  `catatan_admin` TEXT DEFAULT NULL,
  `catatan_owner` TEXT DEFAULT NULL,
  `reviewed_by` BIGINT(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_approval_requests_reviewed_by_foreign` (`reviewed_by`),
  KEY `invoice_approval_requests_invoice_id_status_index` (`invoice_id`, `status`),
  KEY `invoice_approval_requests_user_id_status_index` (`user_id`, `status`),
  CONSTRAINT `invoice_approval_requests_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `penjualan_tokabe` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_approval_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoice_approval_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `penjualan_tokabe`
  ADD COLUMN IF NOT EXISTS `is_custom` TINYINT(1) NOT NULL DEFAULT 0 AFTER `nomor_invoice`;


-- -----------------------------------------------------------------------------
-- 7. SINKRONISASI TABEL MIGRATIONS LARAVEL
-- Agar saat menjalankan 'php artisan migrate' di hosting tidak terjadi konflik
-- -----------------------------------------------------------------------------
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_09_28_000003_create_rekening_table', 99
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_09_28_000003_create_rekening_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_03_000003_create_items_and_link_to_tokabe_invoice_lines', 99
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_03_000003_create_items_and_link_to_tokabe_invoice_lines');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_20_152000_add_is_custom_to_penjualan_tokabe_table', 99
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_20_152000_add_is_custom_to_penjualan_tokabe_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_22_000002_create_invoice_approval_requests_table', 99
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_22_000002_create_invoice_approval_requests_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_28_000001_create_beban_belanja_tables', 99
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_28_000001_create_beban_belanja_tables');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_28_000002_add_anggaran_id_to_beban_belanja_table', 99
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_28_000002_add_anggaran_id_to_beban_belanja_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_29_000001_add_cicil_status_to_pembelian_table', 99
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_29_000001_add_cicil_status_to_pembelian_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_29_201943_create_pembelian_pembayaran_table', 99
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_29_201943_create_pembelian_pembayaran_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_30_000001_convert_belum_lunas_to_cicil_in_pembelian_table', 99
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_30_000001_convert_belum_lunas_to_cicil_in_pembelian_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_02_000001_add_bank_and_nomor_rekening_to_budget_table', 99
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_11_02_000001_add_bank_and_nomor_rekening_to_budget_table');

COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- SELESAI: Database hosting Anda telah siap digunakan dengan fitur-fitur terbaru.
-- =============================================================================

