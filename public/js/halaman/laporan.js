document.addEventListener('DOMContentLoaded', function () {
  var selectPerusahaan = document.getElementById('perusahaanChoice');
  var changePerusahaan = document.getElementById('changePerusahaan');

  if (!selectPerusahaan || !changePerusahaan) {
    return;
  }

  selectPerusahaan.addEventListener('change', function () {
    var selectedOption = selectPerusahaan.options[selectPerusahaan.selectedIndex].text;
    changePerusahaan.textContent = 'Laporan Penjualan  ' + selectedOption
    // var selectedOption = selectPerusahaan.value;

   
  });
});

// document.addEventListener('DOMContentLoaded', function () {
//   var perusahaanTerpilih = document.getElementById('perusahaanTerpilih');
//   var invoiceTerpilih = document.getElementById('invoiceTerpilih');
//   var invoiceTerpilihCstm = document.getElementById('invoiceTerpilihCstm');
//   var invoiceSlct = document.getElementsByClassName('invoice');

//   perusahaanTerpilih.addEventListener('change', function () {
//       var selectedPerusahaan = perusahaanTerpilih.value;
//      console.log(selectedPerusahaan);
//       if (selectedPerusahaan) {
//           invoiceTerpilih.disabled = false;
//           invoiceTerpilihCstm.disabled = false;
//           invoiceSlct.disabled = false;

//           // Mengambil data invoice berdasarkan perusahaan yang dipilih
//           fetch('/getInvoices/' + selectedPerusahaan)
//               .then(response => response.json())
//               .then(data => {
//                   // Menghapus opsi yang ada sebelumnya
//                   invoiceTerpilih.innerHTML = '<option value="semua" selected>Semua Invoice</option>';
//                   invoiceTerpilihCstm.innerHTML = '<option value="semua" selected>Semua Invoice</option>';
//                   invoiceSlct.innerHTML = '<option value="semua" selected>Semua Invoice</option>';
                  
//                   // Menambahkan opsi-opsi baru berdasarkan data invoice
//                   data.forEach(invoice => {
//                       var option = document.createElement('option');
//                       option.value = invoice.id;
//                       option.text = invoice.nama_invoice;
//                       invoiceTerpilih.add(option);
//                       invoiceTerpilihCstm.add(option);
//                       invoiceSlct.add(option);
//                   });
//               })
//               .catch(error => console.error('Error:', error));
//       } else {
//           // Menonaktifkan dan menghapus opsi jika perusahaan tidak dipilih
//           invoiceTerpilih.disabled = true;
//           invoiceTerpilihCstm.disabled = true;
//           invoiceSlct.disabled = true;
//           invoiceTerpilih.innerHTML = '<option value="semua"  selected>Semua Invoice</option>';
//           invoiceTerpilihCstm.innerHTML = '<option value="semua"  selected>Semua Invoice</option>';
//           invoiceSlct.innerHTML = '<option value="semua"  selected>Semua Invoice</option>';
//       }
//   });
// });
document.addEventListener('DOMContentLoaded', function () {
  var perusahaanTerpilih = document.getElementById('perusahaanTerpilih');
  var invoiceTerpilih = document.getElementById('invoiceTerpilih');
  // var invoiceTerpilihCstm = document.getElementById('invoiceTerpilihCstm');
  var invoiceSlct = document.getElementsByClassName('invoice');
  var perusahaanSlct = document.getElementsByClassName('perusahaanHidden');

  if (!perusahaanTerpilih) {
    return;
  }
   
  perusahaanTerpilih.addEventListener('change', function () {
    var selectedPerusahaan = perusahaanTerpilih.value;
    Array.from(perusahaanSlct).forEach(function (element) {
      element.value = selectedPerusahaan;
  });

    console.log(selectedPerusahaan);

    if (selectedPerusahaan) {
      invoiceTerpilih.disabled = false;
      // invoiceTerpilihCstm.disabled = false;

      // Menangani elemen dengan class invoice
      Array.from(invoiceSlct).forEach(function (element) {
        element.disabled = false;
        element.innerHTML = '<option value="semua" selected>Semua Invoice</option>';
      });

      // Mengambil data invoice berdasarkan perusahaan yang dipilih
      fetch('/invoice/getInvoices/' + selectedPerusahaan)
        .then(response => response.json())
        .then(data => {
          // Menambahkan opsi-opsi baru berdasarkan data invoice
          data.forEach(invoice => {
            // Menangani elemen dengan id invoiceTerpilih
            var optionTerpilih = document.createElement('option');
            optionTerpilih.value = invoice.id;
            optionTerpilih.text = invoice.nama_invoice;
            // invoiceTerpilih.add(optionTerpilih);

            // Menangani elemen dengan id invoiceTerpilihCstm
            var optionTerpilihCstm = document.createElement('option');
            optionTerpilihCstm.value = invoice.id;
            optionTerpilihCstm.text = invoice.nama_invoice;
            // invoiceTerpilihCstm.add(optionTerpilihCstm);

            // Menangani elemen-elemen dengan class invoice
            Array.from(invoiceSlct).forEach(function (element) {
              var optionSlct = document.createElement('option');
              optionSlct.value = invoice.id;
              optionSlct.text = invoice.nama_invoice;
              element.add(optionSlct);
            });
          });
        })
        .catch(error => console.error('Error:', error));
    } else {
      invoiceTerpilih.disabled = true;
      // invoiceTerpilihCstm.disabled = true;

      // Menangani elemen-elemen dengan class invoice
      Array.from(invoiceSlct).forEach(function (element) {
        element.disabled = true;
        element.innerHTML = '<option value="semua" selected>Semua Invoice</option>';
      });
    }
  });
});
