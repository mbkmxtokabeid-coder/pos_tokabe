function autoCalc(v) {

  var row = v.closest('tr');
  var index = Array.from(row.parentNode.children).indexOf(row) + 1;


  var harga = document.getElementById('productRate-' + index).value;
  var unformattedHarga = parseFloat(harga.replace(/,/g, ''));
  var qty = parseFloat(document.getElementById('product-qty-' + index).value);

  if (!isNaN(unformattedHarga) && !isNaN(qty)) {
    var jumlah = unformattedHarga * qty;
    document.getElementById('productPrice-' + index).value = jumlah.toLocaleString();
    getTotal();
  }
}
function getTotal() {
  var sum = 0;
  var semuaHarga = document.querySelectorAll('[id^="productPrice-"]');

  for (let i = 0; i < semuaHarga.length; i++) {
    const harga = parseFloat(semuaHarga[i].value.replace(/,/g, ''));
    if (!isNaN(harga)) {
      sum += harga;
    }
  }

  document.getElementById('total-harga').value = sum.toLocaleString();
  document.getElementById('total-pembelian').value = sum.toLocaleString();
  dP();
  totalPembayaran()
  sisaPembayaran();
}

function dP() {
  var divLabel = document.getElementById("div-label");
  var divElement = document.getElementById("dp");
  var totalHarga = parseFloat(document.getElementById('total-harga').value.replace(/,/g, ''));
  var jnsPem = document.getElementById('choices-payment-type').value;
  console.log(totalHarga);
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
  var totalHargaVal = totalHargaElem ? totalHargaElem.value.replace(/,/g, '').trim() : '';
  var totalHarga = parseFloat(totalHargaVal);
  if (isNaN(totalHarga)) {
    totalHarga = 0;
  }

  var inputPpn = document.getElementById('input-ppn');
  var inputPph = document.getElementById('input-pph');

  var ppnPersen = inputPpn ? (parseFloat(inputPpn.value.replace(/,/g, '.')) || 0) : 0;
  var pphPersen = inputPph ? (parseFloat(inputPph.value.replace(/,/g, '.')) || 0) : 0;

  var nominalPpn = (ppnPersen > 0) ? Math.round((ppnPersen / 100) * totalHarga) : 0;
  var nominalPph = (pphPersen > 0) ? Math.round((pphPersen / 100) * totalHarga) : 0;

  total = Math.max(0, Math.round(totalHarga + nominalPpn - nominalPph));

  var totalField = document.getElementById('total-pembelian');
  if (totalField) {
    totalField.value = Math.round(total).toLocaleString();
  }

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

  var rowKodePpn = document.getElementById('row-kode-ppn');
  if (rowKodePpn) {
    rowKodePpn.style.display = ppnPersen > 0 ? 'flex' : 'none';
  }

  sisaPembayaran();
}

function sisaPembayaran() {
  var totalPemElem = document.getElementById('total-pembelian');
  var dpElem = document.getElementById('input-dp');
  var sisaPemElem = document.getElementById('sisa-pembayaran');
  if (!totalPemElem || !sisaPemElem) return;

  var totalPem = parseFloat(totalPemElem.value.replace(/,/g, ''));
  if (isNaN(totalPem)) totalPem = 0;
  var dp = dpElem ? parseFloat(dpElem.value.replace(/,/g, '')) : 0;
  if (isNaN(dp)) dp = 0;

  var sisaPem = Math.max(0, totalPem - dp);
  sisaPemElem.value = Math.round(sisaPem).toLocaleString();
}

// Event listener
if (document.getElementById('total-pembelian')) {
  document.getElementById('total-pembelian').addEventListener('change', sisaPembayaran);
  document.getElementById('total-pembelian').addEventListener('input', sisaPembayaran);
}
if (document.getElementById('input-dp')) {
  document.getElementById('input-dp').addEventListener('change', sisaPembayaran);
  document.getElementById('input-dp').addEventListener('input', sisaPembayaran);
}
if (document.getElementById('total-harga')) {
  document.getElementById('total-harga').addEventListener('input', dP);
  document.getElementById('total-harga').addEventListener('change', dP);
}
if (document.getElementById('choices-payment-type')) {
  document.getElementById('choices-payment-type').addEventListener('change', function() {
    dP();
    sisaPembayaran();
  });
}
if (document.getElementById('input-ppn')) {
  document.getElementById('input-ppn').addEventListener('input', totalPembayaran);
  document.getElementById('input-ppn').addEventListener('change', totalPembayaran);
}
if (document.getElementById('input-pph')) {
  document.getElementById('input-pph').addEventListener('input', totalPembayaran);
  document.getElementById('input-pph').addEventListener('change', totalPembayaran);
}
// document.getElementById('choices-invoice').addEventListener('change', new_link);

var count = document.querySelectorAll("#newlink tr").length;
var usedIds = [];
console.log(count);
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

  count = smallestUnusedId;

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
        '" name="deskripsi_item[]" rows="2" placeholder="Deskripsi Item"></textarea></td><td><select class="form-control bg-light border-0" id="satuan-' + count + '" name="satuan[]" required><option value="">Pilih</option><option value="Pcs">Pcs</option><option value="Lainnya">Lainnya</option></select></td><td><input type="text" class="form-control product-price bg-light border-0" id="productRate-' +
        count +
        '" name="hrg[]" data-cleave=\'{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }\' placeholder="Rp. 0.000" onChange="autoCalc(this)"  required /></td><td><div class="input-step"><input type="number" class="text-center" id="product-qty-' +
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
      // removeItem();
    });
}
document.addEventListener("DOMContentLoaded", function () {
  totalPembayaran();
  sisaPembayaran();
});


remove();

// Batas
function remove() {
  Array.from(document.querySelectorAll(".product-removal a")).forEach(function (e) {
    e.addEventListener("click", function (e) {
      removeItem(e);
    });
  });
}

// batas
function resetRow() {
  Array.from(document.getElementById("newlink").querySelectorAll("tr")).forEach(function (e, t) {
    t += 1;
    e.querySelector(".product-id").innerHTML = t;
    e.id = t; // Menetapkan ulang ID elemen

    // Mengubah ID semua elemen dalam row
    e.querySelectorAll("[id^='productName-'], [id^='productDetails-'], [id^='satuan-'], [id^='productRate-'], [id^='product-qty-'],[id^='productPrice-']").forEach(function (elem) {
      var oldId = elem.id;
      var newId = oldId.replace(/-\d+$/, '-' + t);
      elem.id = newId;
    });
  });
}

function removeItem(e) {
  var row = e.target.closest("tr");
  var id = parseInt(row.id);
  row.remove();
  resetRow();
  updateValues();

  // Menghapus ID dari daftar usedIds
  var index = usedIds.indexOf(id);
  if (index !== -1) {
    usedIds.splice(index, 1);
  }
}

function updateValues() {
  getTotal();
  sisaPembayaran();
}
