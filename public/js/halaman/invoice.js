// formatting
var cleaveDelimiters, cleaveBlocks, hargaModal, hargaJual, cleaveNumeral;
document.querySelector("#cleave-delimiters") && (cleaveDelimiters = new Cleave("#cleave-delimiters", { delimiters: [".", ".", "-"], blocks: [3, 3, 3, 2], uppercase: !0 })),
  document.querySelector("#cleave-prefix") && (cleavePrefix = new Cleave("#cleave-prefix", { prefix: "PREFIX", delimiter: "-", blocks: [6, 4, 4, 4], uppercase: !0 })),
  // Numeral Mask
  document.querySelector("#cleave-numeral") && (cleaveNumeral = new Cleave("#cleave-numeral", { numeral: !0, numeralThousandsGroupStyle: "thousand" })),
  document.querySelector("#phone-number") && (cleaveNumeral = new Cleave("#phone-number", { delimiters: [" "], blocks: [4, 4, 4, 1] }))
  ;
document.querySelectorAll('[data-cleave]').forEach(function (element) {
  var cleaveConfig = JSON.parse(element.getAttribute('data-cleave'));
  new Cleave(element, cleaveConfig);
});


document.addEventListener('DOMContentLoaded', function () {
  var selectInvoice = document.getElementById('choices-invoice');
  var invoicenoInput = document.getElementById('invoicenoInput');

  if (selectInvoice && selectInvoice.tagName === 'SELECT') {
    selectInvoice.addEventListener('change', function () {
      var selectedOption = selectInvoice.value;

      fetch('invoices/' + selectedOption)
        .then(response => response.json())
        .then(data => {
          invoicenoInput.value = data.kode_invoice;
        })
        .catch(error => {
          console.error(error);
        });
    });
  }
});

!(function () {
  "use strict";
  window.addEventListener(
    "load",
    function () {
      var t = document.getElementsByClassName("needs-validation");
      t &&
        Array.prototype.filter.call(t, function (e) {
          e.addEventListener(
            "submit",
            function (t) {
              !1 === e.checkValidity() &&
                (t.preventDefault(), t.stopPropagation()),
                e.classList.add("was-validated");
            },
            !1
          );
        });
    },
    !1
  );
})();

// Baru 2
document.addEventListener('DOMContentLoaded', function () {
  var selectInvoice = document.getElementById('choices-invoice');
  var productNames = document.querySelectorAll('[id^="productName-"]');

  if (selectInvoice && selectInvoice.tagName === 'SELECT') {
    selectInvoice.addEventListener('change', function () {
      var selectedOption = selectInvoice.value;

      productNames.forEach(function (productName) {
        if (productName.name && productName.name.indexOf('item_id[') === 0) return;

        var id = productName.id.split('-')[1]; // Ambil ID dinamis

        fetch('/invoice/barang/' + selectedOption, {
          method: 'POST', // Ubah metode pengiriman data menjadi POST
          body: JSON.stringify({ selected: selectedOption }),
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
        }) // Perbarui URL dengan pilihan
          .then(response => response.json())
          .then(data => {
            var jenisBarang = data.jenis_barang;

            // Perbarui elemen <select> dengan id dinamis
            var selectElement = document.getElementById('productName-' + id);
            selectElement.innerHTML = ''; // Kosongkan elemen sebelumnya

            // Tambahkan opsi baru ke elemen <select>
            jenisBarang.forEach(function (jenis) {
              var option = document.createElement('option');
              option.value = jenis;
              option.textContent = jenis;
              selectElement.appendChild(option);
            });
          })
          .catch(error => {
            console.error(error);
          });
      });
    });
  }
});





document.addEventListener("DOMContentLoaded", function () {
  var selectOrderBy = document.getElementById("choices-orderBy");
  var divSales = document.getElementById("divSales");

  function updateOrderBy() {
    if (!selectOrderBy) return;
    var orderVal = (selectOrderBy.value || "").trim();

    // Toggle form Sales jika memilih Sales
    if (divSales) {
      if (orderVal.toLowerCase() === "sales") {
        divSales.style.display = "block";
      } else {
        divSales.style.display = "none";
      }
    }

    // Jika user memilih procurement, ubah payment method otomatis jadi PO
    if (orderVal.toLowerCase() === "procurement" || orderVal.toLowerCase() === "prokuremen") {
      var paymentSelect = document.getElementById("choices-payment-type");
      if (paymentSelect) {
        paymentSelect.value = "PO";

        // Dukungan jika choices.js terpasang
        if (paymentSelect.choices) {
          try {
            paymentSelect.choices.setChoiceByValue("PO");
          } catch (e) {}
        } else if (paymentSelect._choices) {
          try {
            paymentSelect._choices.setChoiceByValue("PO");
          } catch (e) {}
        }

        // Update UI choices wrapper jika ada
        var choicesParent = paymentSelect.closest(".choices");
        if (choicesParent) {
          var singleItem = choicesParent.querySelector(".choices__list--single .choices__item");
          if (singleItem) {
            singleItem.innerText = "PO";
            singleItem.setAttribute("data-value", "PO");
          }
        }

        // Pemicu event change standar
        paymentSelect.dispatchEvent(new Event("change", { bubbles: true }));
      }
    }
  }

  if (selectOrderBy) {
    selectOrderBy.addEventListener("change", updateOrderBy);
    updateOrderBy();
  }
});

// METODE PEMBAYARAN
document.addEventListener("DOMContentLoaded", function () {
  var selectElement = document.getElementById("choices-payment-type");
  var divElement = document.getElementById("alamatPembayaran");
  var divElement2 = document.getElementById("alamatPembayaran2");
  var divElement3 = document.getElementById("alamatPembayaran3");

  if (!selectElement) return;

  function updatePaymentVisibility() {
    var val = selectElement.value || "";
    if (val.includes("Transfer") || val.includes("PO")) {
      if (divElement) divElement.style.display = "block";
      if (divElement2) divElement2.style.display = "block";
      if (divElement3) divElement3.style.display = "block";
    } else {
      if (divElement) divElement.style.display = "none";
      if (divElement2) divElement2.style.display = "none";
      if (divElement3) divElement3.style.display = "none";
    }
  }

  selectElement.addEventListener("change", updatePaymentVisibility);
  updatePaymentVisibility();
});
