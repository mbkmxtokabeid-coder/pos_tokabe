-- =============================================================================
-- SQL SCRIPT: Perubahan Database Fitur Invoice Custom & Optimasi Index
-- Proyek: Tokabe Invoice
-- Tanggal: 2026-10-21
-- =============================================================================

-- -----------------------------------------------------------------------------
-- 1. FITUR UTAMA: Tambah Kolom is_custom pada tabel penjualan_tokabe
-- -----------------------------------------------------------------------------
-- Menambahkan kolom boolean is_custom (0 = Invoice Biasa, 1 = Invoice Custom)
-- dan index untuk performa filter pemisahan data invoice.

ALTER TABLE `penjualan_tokabe` 
ADD COLUMN `is_custom` TINYINT(1) NOT NULL DEFAULT 0 AFTER `nomor_invoice`,
ADD INDEX `idx_pt_is_custom` (`is_custom`);

-- Pastikan semua data invoice yang sudah ada bernilai 0 (Biasa)
UPDATE `penjualan_tokabe` 
SET `is_custom` = 0 
WHERE `is_custom` IS NULL;

-- Daftarkan migration ke tabel migrations Laravel (agar status migrate tersinkron)
INSERT INTO `migrations` (`migration`, `batch`) 
SELECT '2026_10_20_152000_add_is_custom_to_penjualan_tokabe_table', IFNULL(MAX(batch), 0) + 1 
FROM `migrations`;


-- -----------------------------------------------------------------------------
-- 2. OPSIONAL: Index Performa Tabel Penjualan & Item (Jika belum ada)
-- -----------------------------------------------------------------------------
-- Mempercepat query filter periode, pencarian status, dan export laporan

-- Index untuk tabel penjualan_tokabe
ALTER TABLE `penjualan_tokabe`
ADD INDEX `idx_pt_tgl_penjualan` (`tgl_penjualan`),
ADD INDEX `idx_pt_status` (`status`),
ADD INDEX `idx_pt_approval` (`approval`),
ADD INDEX `idx_pt_deleted_tgl` (`deleted_at`, `tgl_penjualan`);

-- Index untuk tabel penjualan_jasa_tokabe (line items invoice)
ALTER TABLE `penjualan_jasa_tokabe`
ADD INDEX `idx_pjt_deleted_at` (`deleted_at`),
ADD INDEX `idx_pjt_penjualan_del` (`penjualan_id`, `deleted_at`);

-- Daftarkan migration index performa ke tabel migrations Laravel
INSERT INTO `migrations` (`migration`, `batch`) 
SELECT '2026_10_20_104027_add_performance_indexes_to_penjualan_tokabe_tables', IFNULL(MAX(batch), 0) + 1 
FROM `migrations`;
