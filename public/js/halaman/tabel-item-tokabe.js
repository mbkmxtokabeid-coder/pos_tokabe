function autoCalc(v) {

  var row = v.closest('tr');
  var index = Array.from(row.parentNode.children).indexOf(row) + 1;

  var harga = document.getElementById('productRate-' + index).value;

  var unformattedHarga = parseFloat(harga.replace(/[.,]/g, ''));
  var qty = parseFloat(document.getElementById('product-qty-' + index).value);

  if (!isNaN(unformattedHarga) && !isNaN(qty)) {
    var jumlah = Math.round(unformattedHarga * qty);
    // formattedJumlah = jumlah.toLocaleString();

    document.getElementById('productPrice-' + index).value = jumlah.toLocaleString();
    getTotal();
  }
}


function getTotal() {
  var sum = 0;
  var semuaHarga = document.querySelectorAll('[id^="productPrice-"]');

  for (let i = 0; i < semuaHarga.length; i++) {
    const harga = parseFloat(semuaHarga[i].value.replace(/[.,]/g, ''));

    if (!isNaN(harga)) {
      sum += harga;
    }
  }

  var roundedSum = Math.round(sum);
  document.getElementById('total-harga').value = roundedSum.toLocaleString();
  document.getElementById('total-pembelian').value = roundedSum.toLocaleString();
  dP();
  totalPembayaran()
  sisaPembayaran();
}

function dP() {
  var divLabel = document.getElementById("div-label");
  var divElement = document.getElementById("dp");
  var totalHarga = parseFloat(document.getElementById('total-harga').value.replace(/[.,]/g, ''));
  var jnsPem = document.getElementById('choices-payment-type').value;

  if (totalHarga >= 500000) {
    if (jnsPem == 'Cash Belum Lunas' || jnsPem == 'Transfer DP') {
      divLabel.style.display = "block";
      divElement.style.display = "block";
    } else {
      divLabel.style.display = "none";
      divElement.style.display = "none";
    }
  } else {
    divLabel.style.display = "none";
    divElement.style.display = "none";
  }
  // sisaPembayaran();
}

function totalPembayaran() {
  var total;
  var totalHargaElem = document.getElementById('total-harga');
  var totalHarga = totalHargaElem ? parseFloat(totalHargaElem.value.replace(/[.,]/g, '')) : 0;
  if (isNaN(totalHarga)) totalHarga = 0;

  var inputPpn = document.getElementById('input-ppn');
  var inputPph = document.getElementById('input-pph');

  var ppnPersen = inputPpn ? (parseFloat(inputPpn.value.replace(/,/g, '.')) || 0) : 0;
  var pphPersen = inputPph ? (parseFloat(inputPph.value.replace(/,/g, '.')) || 0) : 0;

  var nominalPpn = (ppnPersen > 0) ? Math.round((ppnPersen / 100) * totalHarga) : 0;
  var nominalPph = (pphPersen > 0) ? Math.round((pphPersen / 100) * totalHarga) : 0;

  total = Math.max(0, Math.round(totalHarga + nominalPpn - nominalPph));

  var totalField = document.getElementById('total-pembelian');
  if (totalField) {
    totalField.value = total.toLocaleString();
  }

  // Update preview nominal labels
  var ppnNominalLabel = document.getElementById('preview-nominal-ppn');
  if (ppnNominalLabel) {
    if (nominalPpn > 0) {
      ppnNominalLabel.innerText = '+ Rp. ' + nominalPpn.toLocaleString('id-ID');
      ppnNominalLabel.style.display = 'block';
    } else {
      ppnNominalLabel.innerText = '';
      ppnNominalLabel.style.display = 'none';
    }
  }

  var pphNominalLabel = document.getElementById('preview-nominal-pph');
  if (pphNominalLabel) {
    if (nominalPph > 0) {
      pphNominalLabel.innerText = '- Rp. ' + nominalPph.toLocaleString('id-ID');
      pphNominalLabel.style.display = 'block';
    } else {
      pphNominalLabel.innerText = '';
      pphNominalLabel.style.display = 'none';
    }
  }

  // Toggle Kode Transaksi PPN row
  var rowKodePpn = document.getElementById('row-kode-ppn');
  if (rowKodePpn) {
    rowKodePpn.style.display = ppnPersen > 0 ? 'flex' : 'none';
  }

  sisaPembayaran();
}
document.getElementById('total-pembelian').addEventListener('change', sisaPembayaran);
document.getElementById('input-dp').addEventListener('change', sisaPembayaran);

function sisaPembayaran() {
  var totalPem = parseFloat(document.getElementById('total-pembelian').value.replace(/[.,]/g, ''));
  var dp = parseFloat(document.getElementById('input-dp').value.replace(/[.,]/g, ''));

  if (!isNaN(dp)) {
    var sisaPem = Math.round(totalPem - dp);
    document.getElementById('sisa-pembayaran').value = sisaPem.toLocaleString();
  }
  else {
    document.getElementById('sisa-pembayaran').value = Math.round(totalPem).toLocaleString();
  }
}

// Event listener
document.getElementById('total-harga').addEventListener('input', dP);
document.getElementById('choices-payment-type').addEventListener('change', dP);
if (document.getElementById('input-ppn')) {
  document.getElementById('input-ppn').addEventListener('input', totalPembayaran);
  document.getElementById('input-ppn').addEventListener('change', totalPembayaran);
}
if (document.getElementById('input-pph')) {
  document.getElementById('input-pph').addEventListener('input', totalPembayaran);
  document.getElementById('input-pph').addEventListener('change', totalPembayaran);
}
document.getElementById('choices-payment-type').addEventListener('change', sisaPembayaran);
remove();
resetRow();
totalPembayaran();

var count = 1;
var usedIds = [];
// console.log(usedIds);
function new_link() {
  Array.from(document.querySelectorAll('.product')).forEach(function (element) {
    var id = parseInt(element.id);
    usedIds.push(id);
  });

  // Menemukan ID terkecil yang belum digunakan
  var smallestUnusedId = 1;
  while (usedIds.includes(smallestUnusedId)) {
    smallestUnusedId++;
  }

  count = smallestUnusedId; // Mengatur ulang nilai count


  var e = document.createElement("tr");
  e.id = count;
  e.className = "product";

  var t =
    '<th scope="row" class="product-id">' +
    count +
    '</th><td class="text-start"><div class="mb-2"><label class="visually-hidden" for="productName" >Item</label><select class="form-select " data-choices data-choices-sorting="true" id="productName-' +
    count +
    '" name="barang_id[]"><option selected disabled>Pilih Item</option>';

  fetch('/invoice/jenis-barang')
    .then(function (response) {
      return response.json();
    })
    .then(function (jenis) {
      jenis.forEach(function (jns) {
        t += '<option value="' + jns.id + '">' + jns.jenis_barang + '</option>';
      });

      t +=
        '</select></div><textarea class="form-control bg-light border-0" id="productDetails-' +
        count +
        '" name="deskripsi_item[]" rows="2" placeholder="Deskripsi Item"></textarea></td><td><select class="form-control bg-light border-0" id="satuan-' + count + '" name="satuan[]" required><option value="">Pilih</option><option value="Pcs">Pcs</option><option value="Set">Set</option><option value="Und">Und</option><option value="Blok">Blok</option><option value="Rim">Rim</option><option value="Lbr">Lbr</option><option value="Ktk">Ktk</option><option value="Unit">Unit</option><option value="Kg">Kg</option><option value="Lainnya">Lainnya</option></select></td><td><input type="text" class="form-control product-price bg-light border-0" id="productRate-' 
        +count +
        '" name="hrg[]" data-cleave=\'{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }\' placeholder="Rp. 0.000" onChange="autoCalc(this)" required /></td><td><div class="input-step"><input type="number" class="text-center" id="product-qty-' +
        count +
        '" name="qty[]" onChange="autoCalc(this)" value="1"></div></td><td class="text-end"><div><input type="text" class="form-control bg-light border-0 product-line-price" id="productPrice-' +
        count +
        '" name="jlh_hrg[]"  placeholder="Rp.0.000" onChange="autoCalc(this)" readonly/></div></td><td class="product-removal"><a class="btn btn-success">Delete</a></td>';

      e.innerHTML = t;
      document.getElementById("newlink").appendChild(e);
      usedIds.push(count);

      var choicesElements = document.querySelectorAll("[data-choices]");
      Array.from(choicesElements).forEach(function (element) {
        new Choices(element, {
          placeholderValue: "This is a placeholder set in the config",
          searchPlaceholderValue: "This is a search placeholder",
        });
      });

      var inputElement = document.getElementById("productRate-" + count);
      var cleaveConfig = JSON.parse(inputElement.getAttribute('data-cleave'));
      new Cleave(inputElement, cleaveConfig);
      remove();
      resetRow();
    });
}


totalPembayaran();
// Event delegation for item row removal
$(document).on('click', '.product-removal a, .product-removal button', function (e) {
  e.preventDefault();
  var row = this.closest("tr.product");
  if (!row) return;

  var totalRows = document.querySelectorAll("#newlink tr.product").length;
  if (totalRows <= 1) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        icon: 'warning',
        title: 'Minimal 1 Item',
        text: 'Invoice Tokabe harus memiliki minimal 1 item baris.',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });
    }
    return;
  }

  var id = parseInt(row.id);
  row.remove();
  resetRow();
  updateValues();

  if (typeof usedIds !== 'undefined') {
    var index = usedIds.indexOf(id);
    if (index !== -1) {
      usedIds.splice(index, 1);
    }
  }

  if (typeof $ !== 'undefined' && $('#invoice_form').length) {
    $('#invoice_form').trigger('change');
  }
});

function remove() {
  // Legacy compatibility - delegation handles clicks
}

function resetRow() {
  var rows = document.getElementById("newlink").querySelectorAll("tr.product");
  Array.from(rows).forEach(function (e, t) {
    var rowNum = t + 1; // 1-based for display
    var rowIdx = t;     // 0-based for array names

    var productIdElem = e.querySelector(".product-id");
    if (productIdElem) productIdElem.innerHTML = rowNum;
    e.id = rowNum;

    // Mengubah ID semua elemen dalam row
    e.querySelectorAll("[id^='productName-'], [id^='productDetails-'], [id^='satuan-'], [id^='productRate-'], [id^='product-qty-'],[id^='productPrice-']").forEach(function (elem) {
      var oldId = elem.id;
      var newId = oldId.replace(/-\d+$/, '-' + rowNum);
      elem.id = newId;
    });

    // Re-index NAME attributes (e.g. barang_id[1] -> barang_id[0])
    if (typeof $ !== 'undefined') {
      $(e).find('select, input, textarea').each(function () {
        var name = $(this).attr('name');
        if (name) {
          var newName = name.replace(/\[\d+\]/, '[' + rowIdx + ']');
          $(this).attr('name', newName);
        }
      });

      // Re-index material container class and tambah-material data-index attribute
      $(e).find('[class*="material-container-"]').each(function () {
        this.className = this.className.replace(/material-container-\d+/, 'material-container-' + rowIdx);
      });
      $(e).find('.btn-tambah-material').attr('data-index', rowIdx);
    }
  });
}

function removeItem(e) {
  var row = e.target.closest("tr");
  if (!row) return;
  var id = parseInt(row.id);
  row.remove();
  resetRow();
  updateValues();

  if (typeof usedIds !== 'undefined') {
    var index = usedIds.indexOf(id);
    if (index !== -1) {
      usedIds.splice(index, 1);
    }
  }
}

function updateValues() {
  getTotal();
  sisaPembayaran();
}


