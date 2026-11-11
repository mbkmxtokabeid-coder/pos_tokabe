-- ============================================================================
-- FILE SQL UPDATE INCREMENTAL TERLENGKAP UNTUK HOSTINGER (TOKABE INVOICE)
-- Tanggal Update   : 11 November 2026
-- Sifat            : AMAN (Non-Destructive & Idempotent)
--                    - Hanya menambahkan tabel/kolom/index baru yang belum ada.
--                    - Tidak menghapus atau menimpa data transaksi yang sudah berjalan.
--                    - Dapat di-import berkali-kali tanpa error "Duplicate Column".
--                    - Kompatibel dengan MySQL 5.7, 8.0, dan MariaDB di phpMyAdmin Hostinger.
-- Cara Penggunaan  :
--   1. Login ke cPanel / hPanel Hostinger.
--   2. Buka phpMyAdmin -> Pilih database website Tokabe Anda.
--   3. Klik tab "Import" di menu atas.
--   4. Klik tombol "Choose File" / "Pilih Berkas" -> Pilih file SQL ini.
--   5. Gulir ke bawah lalu klik tombol "Kirim" / "Go".
-- ============================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET NAMES utf8mb4;
START TRANSACTION;

-- ----------------------------------------------------------------------------
-- 1. TABEL budget: Tambah Kolom Bank dan Nomor Rekening
-- ----------------------------------------------------------------------------
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'budget' AND COLUMN_NAME = 'bank');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `budget` ADD COLUMN `bank` VARCHAR(100) NULL AFTER `anggaran`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'budget' AND COLUMN_NAME = 'nomor_rekening');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `budget` ADD COLUMN `nomor_rekening` VARCHAR(100) NULL AFTER `bank`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ----------------------------------------------------------------------------
-- 2. TABEL pembelian: Kolom Approval, Jatuh Tempo, & Status Cicil
-- ----------------------------------------------------------------------------
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pembelian' AND COLUMN_NAME = 'approval');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `pembelian` ADD COLUMN `approval` VARCHAR(191) DEFAULT ''Lock'' AFTER `token`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pembelian' AND COLUMN_NAME = 'approved_at');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `pembelian` ADD COLUMN `approved_at` TIMESTAMP NULL AFTER `approval`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pembelian' AND COLUMN_NAME = 'tanggal_jto');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `pembelian` ADD COLUMN `tanggal_jto` DATE NULL AFTER `tanggal`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pembelian' AND COLUMN_NAME = 'tgl_lunas');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `pembelian` ADD COLUMN `tgl_lunas` DATE NULL AFTER `tgl_jto`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Perbarui status 'Belum Lunas' menjadi 'Cicil'
UPDATE `pembelian` SET `status` = 'Cicil' WHERE `status` = 'Belum Lunas';
ALTER TABLE `pembelian` MODIFY COLUMN `status` ENUM('Lunas', 'Cicil') NOT NULL DEFAULT 'Cicil';

-- ----------------------------------------------------------------------------
-- 3. TABEL BARU: pembelian_pembayaran (Riwayat Cicilan Pembelian Vendor)
-- ----------------------------------------------------------------------------
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
  KEY `pembelian_pembayaran_tanggal_bayar_index` (`tanggal_bayar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migrasikan DP lama ke riwayat termin pertama jika belum ada riwayatnya
INSERT INTO `pembelian_pembayaran` (
  `id`, `pembelian_id`, `cicilan_ke`, `tanggal_bayar`, `jumlah_bayar`, 
  `anggaran_id`, `status_approval`, `catatan`, `created_at`, `updated_at`
)
SELECT 
  UUID(),
  p.id,
  1,
  COALESCE(p.tanggal, CURDATE()),
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

-- ----------------------------------------------------------------------------
-- 4. TABEL BARU: rekening (Master Nomor Rekening Resmi Tokabe)
-- ----------------------------------------------------------------------------
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

INSERT INTO `rekening` (`id`, `nama_bank`, `atas_nama`, `nomor_rekening`, `keterangan`, `status`, `created_at`, `updated_at`)
SELECT 1, 'BSI', 'PT. Total Karya Berkah', '3557999999', 'Rekening Utama Pembayaran Invoice Tokabe', 'Aktif', NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `rekening` WHERE `id` = 1);

-- ----------------------------------------------------------------------------
-- 5. TABEL BARU: kategori_beban & beban_belanja (Operasional Harian)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `kategori_beban` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kategori` VARCHAR(100) NOT NULL,
  `deskripsi` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `approval` VARCHAR(30) NOT NULL DEFAULT 'Lock',
  `approved_at` TIMESTAMP NULL DEFAULT NULL,
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
  KEY `beban_belanja_nomor_beban_index` (`nomor_beban`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pastikan kolom tambahan pada beban_belanja ada jika tabel sudah pernah dibuat sebelumnya
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'beban_belanja' AND COLUMN_NAME = 'anggaran_id');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `beban_belanja` ADD COLUMN `anggaran_id` BIGINT(20) UNSIGNED NULL AFTER `nominal`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'beban_belanja' AND COLUMN_NAME = 'approval');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `beban_belanja` ADD COLUMN `approval` VARCHAR(30) NOT NULL DEFAULT ''Lock'' AFTER `admin_id`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'beban_belanja' AND COLUMN_NAME = 'approved_at');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `beban_belanja` ADD COLUMN `approved_at` TIMESTAMP NULL DEFAULT NULL AFTER `approval`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ----------------------------------------------------------------------------
-- 6. TABEL BARU: invoice_approval_requests (Izin Edit / Hapus Faktur & Transaksi)
-- ----------------------------------------------------------------------------
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
  KEY `invoice_approval_requests_invoice_id_status_index` (`invoice_id`, `status`),
  KEY `invoice_approval_requests_user_id_status_index` (`user_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 7. TABEL BARU: kategori_invoice (Modul Kategori & Format Penomoran Invoice)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `kategori_invoice` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kategori` VARCHAR(120) NOT NULL,
  `keterangan` TEXT DEFAULT NULL,
  `use_custom_format` TINYINT(1) NOT NULL DEFAULT 1,
  `prefix` VARCHAR(40) DEFAULT 'INV',
  `format_nomor` VARCHAR(150) NOT NULL DEFAULT '{PREFIX}/{YEAR}/{NUMBER}',
  `padding_digit` TINYINT(3) UNSIGNED NOT NULL DEFAULT 3,
  `reset_periode` ENUM('yearly','monthly','never') NOT NULL DEFAULT 'yearly',
  `template_settings` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`template_settings`)),
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kategori_invoice` (`nama_kategori`, `keterangan`, `use_custom_format`, `prefix`, `format_nomor`, `padding_digit`, `reset_periode`, `template_settings`, `is_active`, `created_at`, `updated_at`)
SELECT 'Invoice Standar Tokabe', 'Format penomoran standar sistem Tokabe (001/TKB/ROM/YYYY)', 0, 'TKB', '{NUMBER}/{PREFIX}/{ROMAN_MONTH}/{YEAR}', 3, 'monthly', '{"judul_invoice":"INVOICE","subjudul_invoice":"","label_penerima":"Kepada Yth.","tampilkan_npwp":true,"tampilkan_termin":true,"catatan_kaki":"Pembayaran mohon ditransfer ke rekening resmi yang tertera pada invoice."}', 1, NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `kategori_invoice` LIMIT 1);

-- ----------------------------------------------------------------------------
-- 8. KOLOM TAMBAHAN PADA penjualan_tokabe (is_custom, is_padi_umkm, kategori_invoice_id)
-- ----------------------------------------------------------------------------
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'penjualan_tokabe' AND COLUMN_NAME = 'is_custom');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `penjualan_tokabe` ADD COLUMN `is_custom` TINYINT(1) NOT NULL DEFAULT 0 AFTER `nomor_invoice`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'penjualan_tokabe' AND COLUMN_NAME = 'is_padi_umkm');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `penjualan_tokabe` ADD COLUMN `is_padi_umkm` TINYINT(1) NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'penjualan_tokabe' AND COLUMN_NAME = 'kategori_invoice_id');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `penjualan_tokabe` ADD COLUMN `kategori_invoice_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL AFTER `nomor_invoice`, ADD INDEX `penjualan_tokabe_kategori_invoice_id_index` (`kategori_invoice_id`)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ----------------------------------------------------------------------------
-- 9. INDEX PERFORMA DASHBOARD & INVOICE (Mempercepat Query Laporan)
-- ----------------------------------------------------------------------------
SET @idx_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'penjualan_tokabe' AND INDEX_NAME = 'idx_pt_tgl_penjualan');
SET @sql = IF(@idx_exists = 0, 'ALTER TABLE `penjualan_tokabe` ADD INDEX `idx_pt_tgl_penjualan` (`tgl_penjualan`)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @idx_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'penjualan_tokabe' AND INDEX_NAME = 'idx_pt_status');
SET @sql = IF(@idx_exists = 0, 'ALTER TABLE `penjualan_tokabe` ADD INDEX `idx_pt_status` (`status`)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @idx_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'penjualan_tokabe' AND INDEX_NAME = 'idx_pt_approval');
SET @sql = IF(@idx_exists = 0, 'ALTER TABLE `penjualan_tokabe` ADD INDEX `idx_pt_approval` (`approval`)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @idx_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'penjualan_tokabe' AND INDEX_NAME = 'idx_pt_deleted_tgl');
SET @sql = IF(@idx_exists = 0, 'ALTER TABLE `penjualan_tokabe` ADD INDEX `idx_pt_deleted_tgl` (`deleted_at`, `tgl_penjualan`)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @idx_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'penjualan_jasa_tokabe' AND INDEX_NAME = 'idx_pjt_deleted_at');
SET @sql = IF(@idx_exists = 0, 'ALTER TABLE `penjualan_jasa_tokabe` ADD INDEX `idx_pjt_deleted_at` (`deleted_at`)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @idx_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'penjualan_jasa_tokabe' AND INDEX_NAME = 'idx_pjt_penjualan_del');
SET @sql = IF(@idx_exists = 0, 'ALTER TABLE `penjualan_jasa_tokabe` ADD INDEX `idx_pjt_penjualan_del` (`penjualan_id`, `deleted_at`)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ----------------------------------------------------------------------------
-- 10. TABEL items (4 Master Item Resmi Tokabe: Barang, Barang dan Jasa, Jasa, Jasa Periklanan)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `items` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_item` VARCHAR(255) NOT NULL,
  `jenis_pajak` ENUM('ppn','pph','ppn_pph','tanpa_pajak') NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `items_nama_item_unique` (`nama_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'penjualan_jasa_tokabe' AND COLUMN_NAME = 'item_id');
SET @sql = IF(@col_exists = 0,
  'ALTER TABLE `penjualan_jasa_tokabe` MODIFY COLUMN `barang_id` BIGINT(20) UNSIGNED NULL, ADD COLUMN `item_id` BIGINT(20) UNSIGNED NULL AFTER `barang_id`',
  'ALTER TABLE `penjualan_jasa_tokabe` MODIFY COLUMN `barang_id` BIGINT(20) UNSIGNED NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

INSERT INTO `items` (`nama_item`, `jenis_pajak`, `created_at`, `updated_at`) VALUES
  ('Barang', 'ppn', NOW(), NOW()),
  ('Barang dan Jasa', 'ppn_pph', NOW(), NOW()),
  ('Jasa', 'pph', NOW(), NOW()),
  ('Jasa Periklanan', 'ppn_pph', NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `jenis_pajak` = VALUES(`jenis_pajak`),
  `updated_at` = NOW();

UPDATE `penjualan_jasa_tokabe`
SET `item_id` = NULL
WHERE `item_id` IS NOT NULL
  AND `item_id` NOT IN (
    SELECT `id` FROM `items`
    WHERE `nama_item` IN ('Barang', 'Barang dan Jasa', 'Jasa', 'Jasa Periklanan')
  );

DELETE FROM `items`
WHERE `nama_item` NOT IN ('Barang', 'Barang dan Jasa', 'Jasa', 'Jasa Periklanan');

-- ----------------------------------------------------------------------------
-- 11. TABEL BARU: spt_masa (Modul SPT Masa PPN Induk CoreTax & PPh Bulanan)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `spt_masa` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomor_spt` VARCHAR(50) DEFAULT NULL,
  `masa_pajak_bulan` TINYINT(3) UNSIGNED NOT NULL,
  `masa_pajak_tahun` SMALLINT(5) UNSIGNED NOT NULL,
  `pembetulan_ke` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  `jenis_pajak` VARCHAR(100) NOT NULL,
  `nomor_billing` VARCHAR(100) DEFAULT NULL,
  `tgl_billing` DATE DEFAULT NULL,
  `tgl_jatuh_tempo` DATE NOT NULL,
  `dpp` BIGINT(20) NOT NULL DEFAULT 0,
  `tarif_persen` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `nominal_pajak` BIGINT(20) NOT NULL DEFAULT 0,
  `status_kb_lb` VARCHAR(30) DEFAULT 'Kurang Bayar',
  `nominal_lebih_bayar` BIGINT(20) NOT NULL DEFAULT 0,
  `opsi_lebih_bayar` VARCHAR(80) DEFAULT NULL,
  `tgl_bayar` DATE DEFAULT NULL,
  `tgl_lapor` DATE DEFAULT NULL,
  `ntpn` VARCHAR(100) DEFAULT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Belum Dibayar',
  `bukti_bayar` VARCHAR(255) DEFAULT NULL,
  `keterangan` TEXT DEFAULT NULL,
  `rincian_spt` LONGTEXT DEFAULT NULL,
  `approval` VARCHAR(30) NOT NULL DEFAULT 'Lock',
  `approved_at` TIMESTAMP NULL DEFAULT NULL,
  `created_by` BIGINT(20) UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `spt_masa_nomor_spt_index` (`nomor_spt`),
  KEY `spt_masa_masa_pajak_tahun_masa_pajak_bulan_index` (`masa_pajak_tahun`,`masa_pajak_bulan`),
  KEY `spt_masa_status_index` (`status`),
  KEY `spt_masa_tgl_bayar_index` (`tgl_bayar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modifikasi status spt_masa menjadi VARCHAR(50) agar mendukung status dinamis (Lunas, Dikompensasikan, dll.)
ALTER TABLE `spt_masa` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'Belum Dibayar';

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'spt_masa' AND COLUMN_NAME = 'pembetulan_ke');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `spt_masa` ADD COLUMN `pembetulan_ke` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0 AFTER `masa_pajak_tahun`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'spt_masa' AND COLUMN_NAME = 'status_kb_lb');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `spt_masa` ADD COLUMN `status_kb_lb` VARCHAR(30) NULL DEFAULT ''Kurang Bayar'' AFTER `nominal_pajak`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'spt_masa' AND COLUMN_NAME = 'nominal_lebih_bayar');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `spt_masa` ADD COLUMN `nominal_lebih_bayar` BIGINT(20) NOT NULL DEFAULT 0 AFTER `status_kb_lb`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'spt_masa' AND COLUMN_NAME = 'opsi_lebih_bayar');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `spt_masa` ADD COLUMN `opsi_lebih_bayar` VARCHAR(80) NULL DEFAULT NULL AFTER `nominal_lebih_bayar`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'spt_masa' AND COLUMN_NAME = 'tgl_lapor');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `spt_masa` ADD COLUMN `tgl_lapor` DATE NULL DEFAULT NULL AFTER `tgl_bayar`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'spt_masa' AND COLUMN_NAME = 'rincian_spt');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `spt_masa` ADD COLUMN `rincian_spt` LONGTEXT NULL DEFAULT NULL AFTER `keterangan`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'spt_masa' AND COLUMN_NAME = 'approval');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `spt_masa` ADD COLUMN `approval` VARCHAR(30) NOT NULL DEFAULT ''Lock'' AFTER `rincian_spt`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'spt_masa' AND COLUMN_NAME = 'approved_at');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `spt_masa` ADD COLUMN `approved_at` TIMESTAMP NULL DEFAULT NULL AFTER `approval`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ----------------------------------------------------------------------------
-- 12. SINKRONISASI TABEL migrations (Mencegah Tabrakan Saat Artisan Migrate)
-- ----------------------------------------------------------------------------
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_09_28_000003_create_rekening_table', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_09_28_000003_create_rekening_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_02_145054_add_satuan_jto_to_pembelian_barang', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_02_145054_add_satuan_jto_to_pembelian_barang');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_02_145131_add_tanggal_jto_to_pembelian', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_02_145131_add_tanggal_jto_to_pembelian');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_02_160000_add_jenis_invoice_to_pembelian', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_02_160000_add_jenis_invoice_to_pembelian');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_02_161000_add_tax_fields_to_pembelian', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_02_161000_add_tax_fields_to_pembelian');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_02_162000_add_jenis_item_to_pembelian_barang', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_02_162000_add_jenis_item_to_pembelian_barang');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_03_000001_add_tanpa_pajak_invoice_type', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_03_000001_add_tanpa_pajak_invoice_type');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_03_000002_expand_jenis_item_pembelian_barang', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_03_000002_expand_jenis_item_pembelian_barang');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_03_000003_create_items_and_link_to_tokabe_invoice_lines', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_03_000003_create_items_and_link_to_tokabe_invoice_lines');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_03_000004_add_approval_to_pembelian_table', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_03_000004_add_approval_to_pembelian_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_14_000001_seed_items_from_active_barang', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_14_000001_seed_items_from_active_barang');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_20_104027_add_performance_indexes_to_penjualan_tokabe_tables', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_20_104027_add_performance_indexes_to_penjualan_tokabe_tables');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_20_152000_add_is_custom_to_penjualan_tokabe_table', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_20_152000_add_is_custom_to_penjualan_tokabe_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_22_000001_add_npwp_and_faktur_pajak_to_penjualan_tokabe', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_22_000001_add_npwp_and_faktur_pajak_to_penjualan_tokabe');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_22_000002_create_invoice_approval_requests_table', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_22_000002_create_invoice_approval_requests_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_28_000001_create_beban_belanja_tables', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_28_000001_create_beban_belanja_tables');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_28_000002_add_anggaran_id_to_beban_belanja_table', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_28_000002_add_anggaran_id_to_beban_belanja_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_29_000001_add_cicil_status_to_pembelian_table', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_29_000001_add_cicil_status_to_pembelian_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_29_201943_create_pembelian_pembayaran_table', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_29_201943_create_pembelian_pembayaran_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_30_000001_convert_belum_lunas_to_cicil_in_pembelian_table', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_30_000001_convert_belum_lunas_to_cicil_in_pembelian_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_02_000001_add_bank_and_nomor_rekening_to_budget_table', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_11_02_000001_add_bank_and_nomor_rekening_to_budget_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_06_000001_add_is_padi_umkm_to_penjualan_tokabe_table', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_11_06_000001_add_is_padi_umkm_to_penjualan_tokabe_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_06_000002_create_spt_masa_table', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_11_06_000002_create_spt_masa_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_06_000003_create_kategori_invoice_table', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_11_06_000003_create_kategori_invoice_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_11_000001_update_spt_masa_for_ppn_induk', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_11_11_000001_update_spt_masa_for_ppn_induk');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_11_000002_add_approval_to_spt_masa_and_beban_belanja', COALESCE((SELECT MAX(`batch`) FROM (SELECT `batch` FROM `migrations`) AS m), 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_11_11_000002_add_approval_to_spt_masa_and_beban_belanja');

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- SELESAI: Database Tokabe di Hostinger kini 100% up-to-date & aman!
-- ============================================================================
