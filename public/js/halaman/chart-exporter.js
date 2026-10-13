/**
 * chart-exporter.js
 * Utilitas ekspor satu halaman grafik lengkap (Bar Chart, Donut Chart, & Keterangan Waktu) ke format PNG.
 * Menggunakan html2canvas dengan skala resolusi tinggi (High-DPI / 2x Scale) dan mendukung Dark Mode.
 */

(function (window, document) {
    'use strict';

    /**
     * Mendeteksi warna latar belakang tema saat ini
     * @param {HTMLElement} containerEl 
     * @returns {string} Hex atau rgb warna latar belakang
     */
    function getThemeBackgroundColor(containerEl) {
        const isDark = document.documentElement.getAttribute('data-layout-mode') === 'dark';
        let bgColor = '#ffffff';

        if (containerEl) {
            const cardEl = containerEl.closest('.card') || containerEl;
            if (cardEl) {
                const computed = window.getComputedStyle(cardEl).backgroundColor;
                if (computed && computed !== 'transparent' && computed !== 'rgba(0, 0, 0, 0)') {
                    bgColor = computed;
                }
            }
        }

        if (isDark && (bgColor === '#ffffff' || bgColor === 'transparent' || bgColor === 'rgb(255, 255, 255)')) {
            bgColor = '#1a1d21'; // Latar belakang kartu default dark theme
        }

        return bgColor;
    }

    /**
     * Memformat tanggal dan waktu sekarang dalam Bahasa Indonesia baku
     * @returns {string} Contoh: "01 Oktober 2026, 09:50 WIB"
     */
    function getIndonesianTimestamp() {
        const now = new Date();
        const options = {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };
        return now.toLocaleDateString('id-ID', options).replace(/\./g, ':') + ' WIB';
    }

    /**
     * Mengambil keterangan periode aktif berdasarkan section grafik
     * @param {string} sectionKey 
     * @returns {string} Keterangan periode
     */
    function getActivePeriodLabel(sectionKey) {
        if (sectionKey === 'barang') {
            const labelEl = document.getElementById('badge-period-text');
            if (labelEl && labelEl.textContent.trim()) {
                return labelEl.textContent.trim();
            }
            return 'Tahun Ini';
        } else if (sectionKey === 'pembayaran') {
            const periodeEl = document.getElementById('badge-payment-period-text') || document.getElementById('periode');
            if (periodeEl && periodeEl.textContent.trim()) {
                return periodeEl.textContent.trim();
            }
            return 'Bulan Ini';
        }
        return 'Periode Aktif';
    }

    /**
     * Fungsi utama untuk mengekspor kartu section ke format PNG
     * @param {string} sectionKey 'pembayaran' atau 'barang'
     */
    function exportSectionToPng(sectionKey) {
        if (typeof html2canvas === 'undefined') {
            console.error('Library html2canvas belum dimuat.');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Pustaka Ekspor Tidak Tersedia',
                    text: 'Library html2canvas tidak ditemukan. Silakan refresh halaman dan coba kembali.'
                });
            } else {
                alert('Pustaka html2canvas belum tersedia.');
            }
            return;
        }

        let targetId = '';
        let fileTitle = 'Laporan-Grafik';

        const now = new Date();
        const yyyy = now.getFullYear();
        const mm = String(now.getMonth() + 1).padStart(2, '0');
        const dd = String(now.getDate()).padStart(2, '0');
        const dateStamp = `${yyyy}${mm}${dd}`;

        if (sectionKey === 'barang') {
            targetId = 'card-statistik-barang';
            fileTitle = `Laporan-Statistik-Jenis-dan-Kategori-Barang-${dateStamp}`;
        } else if (sectionKey === 'pembayaran') {
            targetId = 'card-aktivitas-pembayaran';
            fileTitle = `Laporan-Aktivitas-Pembayaran-dan-Struktur-Status-${dateStamp}`;
        } else {
            targetId = sectionKey;
            fileTitle = `Laporan-${sectionKey}-${dateStamp}`;
        }

        const targetEl = document.getElementById(targetId);
        if (!targetEl) {
            console.error('Target elemen untuk ekspor tidak ditemukan:', targetId);
            return;
        }

        const periodeLabel = getActivePeriodLabel(sectionKey);
        const waktuCetak = getIndonesianTimestamp();
        const bgColor = getThemeBackgroundColor(targetEl);
        const isDark = document.documentElement.getAttribute('data-layout-mode') === 'dark';

        // Tampilkan loading dialog
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Mengekspor Gambar PNG',
                html: '<div class="py-2"><span class="text-muted fs-13">Sedang menyusun grafik batang, donut chart, rincian, dan keterangan waktu...</span></div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        html2canvas(targetEl, {
            scale: 2, // Resolusi tinggi tajam (High-DPI 2x)
            useCORS: true,
            allowTaint: true,
            backgroundColor: bgColor,
            logging: false,
            onclone: function (clonedDoc) {
                const clonedCard = clonedDoc.getElementById(targetId);
                if (!clonedCard) return;

                // 1. Sembunyikan elemen tombol interaktif, dropdown filter, dan form input
                const hideSelectors = [
                    'button',
                    '.btn',
                    '.btn-group',
                    '#panel-custom-filter',
                    '#panel-custom-filter-payment',
                    '#payment-custom-filter-box',
                    'select',
                    'input',
                    '#spinner-barang',
                    '.no-export'
                ];
                clonedCard.querySelectorAll(hideSelectors.join(',')).forEach(function (el) {
                    el.style.display = 'none';
                });

                // 2. Buka batas tinggi (max-height) pada tabel bila ada
                clonedCard.querySelectorAll('.table-responsive').forEach(function (el) {
                    el.style.maxHeight = 'none';
                    el.style.overflow = 'visible';
                    el.style.height = 'auto';
                });

                // 3. Pastikan dimensi SVG pada grafik ApexCharts terdefinisi eksplisit
                clonedCard.querySelectorAll('.apexcharts-canvas').forEach(function (canvasWrap) {
                    const svg = canvasWrap.querySelector('svg');
                    if (svg) {
                        const rect = canvasWrap.getBoundingClientRect();
                        if (rect.width > 0) svg.setAttribute('width', rect.width);
                        if (rect.height > 0) svg.setAttribute('height', rect.height);
                    }
                });

                // 4. Tambahkan header resmi informasi laporan & waktu ekspor
                const metaHeader = clonedDoc.createElement('div');
                metaHeader.style.cssText = `
                    padding: 14px 18px;
                    margin-bottom: 16px;
                    border-radius: 8px;
                    background: ${isDark ? 'rgba(59, 130, 246, 0.15)' : 'rgba(59, 130, 246, 0.08)'};
                    border: 1px solid ${isDark ? 'rgba(59, 130, 246, 0.4)' : 'rgba(59, 130, 246, 0.25)'};
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    font-family: inherit;
                `;
                metaHeader.innerHTML = `
                    <div>
                        <div style="font-weight: 700; font-size: 16px; color: #3b82f6; letter-spacing: 0.5px;">TOKABE KASIR & INVOICE</div>
                        <div style="font-size: 12px; color: ${isDark ? '#a1a1aa' : '#64748b'}; margin-top: 2px;">Dokumen Laporan Visual Grafik</div>
                    </div>
                    <div style="text-align: right; font-size: 12px; color: ${isDark ? '#e2e8f0' : '#1e293b'}; line-height: 1.5;">
                        <div><span style="color: ${isDark ? '#94a3b8' : '#64748b'};">Keterangan Periode:</span> <strong>${periodeLabel}</strong></div>
                        <div><span style="color: ${isDark ? '#94a3b8' : '#64748b'};">Waktu Cetak:</span> <strong>${waktuCetak}</strong></div>
                    </div>
                `;
                clonedCard.insertBefore(metaHeader, clonedCard.firstChild);

                // 5. Tambahkan footer resmi
                const metaFooter = clonedDoc.createElement('div');
                metaFooter.style.cssText = `
                    padding: 10px 18px;
                    margin-top: 16px;
                    border-top: 1px solid ${isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'};
                    font-size: 11px;
                    color: ${isDark ? '#71717a' : '#94a3b8'};
                    display: flex;
                    justify-content: space-between;
                    font-family: inherit;
                `;
                metaFooter.innerHTML = `
                    <span>Sistem Manajemen Kasir & Invoice Tokabe</span>
                    <span>Dicetak otomatis pada: ${waktuCetak}</span>
                `;
                clonedCard.appendChild(metaFooter);
            }
        }).then(function (canvas) {
            if (typeof Swal !== 'undefined') Swal.close();

            const imgData = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.download = fileTitle + '.png';
            link.href = imgData;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Laporan grafik berhasil diekspor ke PNG!'
                });
            }
        }).catch(function (error) {
            if (typeof Swal !== 'undefined') {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengekspor Grafik',
                    text: 'Terjadi kendala: ' + (error.message || error)
                });
            } else {
                alert('Gagal mengekspor grafik: ' + (error.message || error));
            }
            console.error('Error saat ekspor section ke PNG:', error);
        });
    }

    // Ekspor fungsi ke scope global
    window.exportSectionToPng = exportSectionToPng;

})(window, document);
