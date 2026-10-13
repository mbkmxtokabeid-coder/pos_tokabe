/**
 * Auto-Save Draft Manager for Tokabe Invoices (Add & Edit)
 * Uses localStorage to preserve form state across page refreshes and browser crashes.
 */

(function ($) {
    'use strict';

    function getStorageKey() {
        var $form = $('#invoice_form');
        if (!$form.length) return null;
        
        var invoiceId = $form.data('invoice-id');
        if (invoiceId) {
            return 'tokabe_edit_invoice_draft_' + invoiceId;
        } else {
            return 'tokabe_add_invoice_draft';
        }
    }

    // Debounce helper
    function debounce(func, wait) {
        var timeout;
        return function () {
            var context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                func.apply(context, args);
            }, wait);
        };
    }

    function saveDraft() {
        var key = getStorageKey();
        if (!key) return;

        var $form = $('#invoice_form');
        if (!$form.length) return;

        var data = {
            saved_at: new Date().toISOString(),
            header: {
                inv: $('[name="inv"]').val() || '',
                pelanggan: $('[name="pelanggan"]').val() || '',
                perusahaan: $('[name="perusahaan"]').val() || '',
                tlp: $('[name="tlp"]').val() || '',
                adm: $('[name="adm"]').val() || '',
                order: $('[name="order"]').val() || '',
                sales: $('[name="sales"]').val() || '',
                tgl_selesai: $('[name="tgl_selesai"]').val() || '',
                jns_pem: $('[name="jns_pem"]').val() || '',
                norek: $('[name="norek"]:checked').val() || '',
                ppn: $('#input-ppn').val() || '',
                kode_ppn: $('#select-kode-ppn').val() || '01',
                pph: $('#input-pph').val() || '',
                dp: $('#input-dp').val() || ''
            },
            items: []
        };

        $('#newlink tr.product').each(function (idx) {
            var $row = $(this);
            var itemData = {
                item_id: $row.find('select[name^="item_id"]').val() || '',
                barang_id: $row.find('select[name^="barang_id"]').val() || '',
                deskripsi_item: $row.find('textarea[name^="deskripsi_item"]').val() || '',
                satuan: $row.find('select[name^="satuan"]').val() || '',
                hrg: $row.find('input[name^="hrg"]').val() || '',
                qty: $row.find('input[name^="qty"]').val() || '',
                jlh_hrg: $row.find('input[name^="jlh_hrg"]').val() || '',
                materials: []
            };

            $row.find('.material-row').each(function () {
                var $mRow = $(this);
                itemData.materials.push({
                    material_id: $mRow.find('.material-select').val() || '',
                    material_panjang: $mRow.find('.material-panjang').val() || '',
                    material_lebar: $mRow.find('.material-lebar').val() || '',
                    material_qty: $mRow.find('.material-qty').val() || '',
                    material_satuan: $mRow.find('.material-satuan').val() || ''
                });
            });

            data.items.push(itemData);
        });

        try {
            localStorage.setItem(key, JSON.stringify(data));
        } catch (e) {
            console.error('Failed to save draft to localStorage', e);
        }
    }

    window.clearInvoiceDraft = function () {
        var key = getStorageKey();
        if (key) {
            localStorage.removeItem(key);
        }
    };

    function restoreDraft() {
        var key = getStorageKey();
        if (!key) return;

        var raw = localStorage.getItem(key);
        if (!raw) return;

        var data;
        try {
            data = JSON.parse(raw);
        } catch (e) {
            return;
        }

        if (!data || !data.header) return;

        var header = data.header;

        // Restore Header Inputs
        if (header.inv) $('[name="inv"]').val(header.inv).trigger('change');
        if (header.pelanggan) $('[name="pelanggan"]').val(header.pelanggan).trigger('change');
        if (header.perusahaan) $('[name="perusahaan"]').val(header.perusahaan);
        if (header.tlp) $('[name="tlp"]').val(header.tlp);
        if (header.adm) $('[name="adm"]').val(header.adm).trigger('change');
        if (header.order) $('[name="order"]').val(header.order).trigger('change');
        if (header.sales) {
            $('[name="sales"]').val(header.sales);
            if ($('#divSales').length) $('#divSales').show();
        }
        if (header.tgl_selesai) $('[name="tgl_selesai"]').val(header.tgl_selesai);
        if (header.jns_pem) $('[name="jns_pem"]').val(header.jns_pem).trigger('change');
        if (header.norek) $('[name="norek"][value="' + header.norek + '"]').prop('checked', true);

        if (header.ppn !== undefined && header.ppn !== null && header.ppn !== '') {
            $('#input-ppn').val(header.ppn).trigger('input');
        }
        if (header.kode_ppn) {
            $('#select-kode-ppn').val(header.kode_ppn).trigger('change');
        }
        if (header.pph !== undefined && header.pph !== null && header.pph !== '') {
            $('#input-pph').val(header.pph).trigger('input');
        }
        if (header.dp) {
            $('#input-dp').val(header.dp).trigger('input');
        }

        // Restore Items
        if (data.items && data.items.length > 0) {
            var existingRowsCount = $('#newlink tr.product').length;

            // Ensure enough item rows exist
            while (existingRowsCount < data.items.length) {
                if (typeof window.new_link === 'function') {
                    window.new_link();
                } else {
                    break;
                }
                existingRowsCount = $('#newlink tr.product').length;
            }

            var $rows = $('#newlink tr.product');
            data.items.forEach(function (item, itemIdx) {
                var $row = $rows.eq(itemIdx);
                if (!$row.length) return;

                if (item.item_id) $row.find('select[name^="item_id"]').val(item.item_id).trigger('change');
                if (item.barang_id) $row.find('select[name^="barang_id"]').val(item.barang_id).trigger('change');
                if (item.deskripsi_item) $row.find('textarea[name^="deskripsi_item"]').val(item.deskripsi_item);
                if (item.satuan) $row.find('select[name^="satuan"]').val(item.satuan).trigger('change');
                if (item.hrg) $row.find('input[name^="hrg"]').val(item.hrg).trigger('change').trigger('input');
                if (item.qty) $row.find('input[name^="qty"]').val(item.qty).trigger('change').trigger('input');
                if (item.jlh_hrg) $row.find('input[name^="jlh_hrg"]').val(item.jlh_hrg);

                // Materials restoration
                if (item.materials && item.materials.length > 0) {
                    item.materials.forEach(function (mat, mIdx) {
                        var $mContainer = $row.find('[class*="material-container-"]');
                        var $mRows = $mContainer.find('.material-row');

                        // If material sub-row does not exist yet, trigger add material button
                        if (mIdx >= $mRows.length) {
                            var $addMatBtn = $row.find('.btn-tambah-material');
                            if ($addMatBtn.length) {
                                $addMatBtn.trigger('click');
                                $mRows = $mContainer.find('.material-row');
                            }
                        }

                        var $targetMRow = $mRows.eq(mIdx);
                        if ($targetMRow.length) {
                            if (mat.material_id) $targetMRow.find('.material-select').val(mat.material_id).trigger('change', [true]);
                            if (mat.material_panjang) $targetMRow.find('.material-panjang').val(mat.material_panjang);
                            if (mat.material_lebar) $targetMRow.find('.material-lebar').val(mat.material_lebar);
                            if (mat.material_qty) $targetMRow.find('.material-qty').val(mat.material_qty);
                            if (mat.material_satuan) $targetMRow.find('.material-satuan').val(mat.material_satuan);
                        }
                    });
                }
            });
        }

        // Trigger Calculations
        if (typeof autoCalc === 'function') {
            $('#newlink tr.product input[name^="hrg"]').each(function () {
                autoCalc(this);
            });
        }
        if (typeof getTotal === 'function') getTotal();
        if (typeof totalPembayaran === 'function') totalPembayaran();
        if (typeof sisaPembayaran === 'function') sisaPembayaran();

        // Show Toast Notice
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Draf Dimuat Kembali',
                text: 'Data inputan sebelumnya berhasil dimuat otomatis. Tekan "Reset Draf" jika ingin memulai dari awal.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            });
        }
    }

    $(document).ready(function () {
        var $form = $('#invoice_form');
        if (!$form.length) return;

        // Restore draft after a slight delay to ensure dynamic scripts & plugins are ready
        setTimeout(function () {
            restoreDraft();
        }, 300);

        // Bind auto-save listener
        var debouncedSave = debounce(saveDraft, 400);

        $(document).on('input change', '#invoice_form input, #invoice_form select, #invoice_form textarea', function () {
            debouncedSave();
        });

        // Add manual reset draft button next to save/update button if not exists
        var $submitBtn = $form.find('button[type="submit"]');
        if ($submitBtn.length && !$('#btn-reset-draft').length) {
            var $resetBtn = $('<button type="button" id="btn-reset-draft" class="btn btn-outline-danger ms-2"><i class="ri-delete-bin-line align-bottom me-1"></i>Reset Draf</button>');
            $submitBtn.after($resetBtn);

            $resetBtn.on('click', function () {
                Swal.fire({
                    title: 'Hapus Draf?',
                    text: 'Apakah Anda yakin ingin mengosongkan draf inputan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#1E293B',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        clearInvoiceDraft();
                        window.location.reload();
                    }
                });
            });
        }

        // Handle Batal / Cancel Button
        $(document).on('click', '.btn-cancel-tokabe', function (e) {
            e.preventDefault();
            var targetUrl = $(this).attr('href');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Batalkan Proses?',
                    text: 'Apakah Anda yakin ingin membatalkan? Draf inputan ini akan dibersihkan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#1E293B',
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Kembali'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        clearInvoiceDraft();
                        window.location.href = targetUrl;
                    }
                });
            } else {
                clearInvoiceDraft();
                window.location.href = targetUrl;
            }
        });

        // Clear draft on successful form submit
        $form.on('submit', function () {
            setTimeout(function() {
                if ($('#loading-spinner').is(':visible') || !$form.find('.is-invalid').length) {
                    clearInvoiceDraft();
                }
            }, 100);
        });
    });

})(jQuery);
