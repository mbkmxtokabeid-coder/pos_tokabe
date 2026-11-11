var cleaveBlocks;
document.querySelector("#phone-number") && (cleaveNumeral = new Cleave("#phone-number", { delimiters: [" "], blocks: [4, 4, 4, 1] }));

function appendSpbRow(count, optionsHtml) {
  var e = document.createElement("tr");
  e.id = count;
  e.className = "product align-middle";

  e.innerHTML =
    '<th scope="row" class="product-id text-center text-muted fw-semibold"></th>' +
    '<td class="text-start">' +
      '<div class="mb-2">' +
        '<label class="visually-hidden" for="productName-' + count + '">Item</label>' +
        '<select class="form-select searchable-item-select" id="productName-' + count + '" name="barang_id[]">' +
          optionsHtml +
        '</select>' +
      '</div>' +
      '<textarea class="form-control" id="productDetails-' + count + '" name="deskripsi_item[]" rows="2" placeholder="Deskripsi Item"></textarea>' +
    '</td>' +
    '<td>' +
      '<select class="form-select" id="satuan-' + count + '" name="satuan[]" required>' +
        '<option value="">Pilih</option>' +
        '<option value="Set">Set</option>' +
        '<option value="Pcs">Pcs</option>' +
        '<option value="Kotak">Kotak</option>' +
        '<option value="Blok">Blok</option>' +
        '<option value="Lbr">Lbr</option>' +
      '</select>' +
    '</td>' +
    '<td>' +
      '<div class="input-step">' +
        '<input type="number" class="form-control text-center" id="product-qty-' + count + '" name="qty[]" value="0">' +
      '</div>' +
    '</td>' +
    '<td class="text-end">' +
      '<div>' +
        '<textarea id="productKeterangan-' + count + '" class="form-control" name="keterangan[]" cols="2" placeholder="Keterangan"></textarea>' +
      '</div>' +
    '</td>' +
    '<td class="product-removal text-center">' +
      '<a href="javascript:void(0)" class="btn btn-hapus-item"><i class="ri-delete-bin-line fs-15 me-1"></i>Hapus</a>' +
    '</td>';

  document.getElementById("newlink").appendChild(e);

  var newSelect = document.getElementById("productName-" + count);
  if (newSelect && typeof window.enhanceSearchableSelect === "function") {
    window.enhanceSearchableSelect(newSelect);
  }

  remove();
  resetRow();
}

var count = 1;
function new_link() {
  var rowId = Date.now(); // Gunakan timestamp agar ID selalu unik
  var templateSelect = document.getElementById("template-options-spb");

  if (templateSelect && templateSelect.innerHTML.trim() !== "") {
    appendSpbRow(rowId, templateSelect.innerHTML);
    return;
  }

  // Fallback: fetch dari endpoint jika template elemen tidak tersedia
  var pathname = window.location.pathname;
  var basePath = '/';
  if (pathname.includes('/tambah-spb')) {
      basePath = pathname.split('/tambah-spb')[0] + '/';
  } else if (pathname.includes('/edit-spb')) {
      basePath = pathname.split('/edit-spb')[0] + '/';
  }
  
  var urlKategori = basePath + 'kategori-barang';
  
  fetch(urlKategori)
    .then(function (response) {
      if (!response.ok) {
          throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(function (kategori) {
      var opts = '<option value="" selected disabled>Pilih Item</option>';
      kategori.forEach(function (ktg) {
        opts += '<option value="' + ktg.id + '">' + ktg.nama_kategori + '</option>';
      });
      appendSpbRow(rowId, opts);
    })
    .catch(function (error) {
      console.error('Error fetching kategori barang:', error);
    });
}

// Batas
function remove() {
  Array.from(document.querySelectorAll(".product-removal a")).forEach(function (e) {
    e.addEventListener("click", function (e) {
      removeItem(e);
    });
  });
}
function resetRow() {
  Array.from(document.getElementById("newlink").querySelectorAll("tr")).forEach(function (e, t) {
    t += 1;
    e.querySelector(".product-id").innerHTML = t;
  });
}
function removeItem(e) {
  var row = e.target.closest("tr");
  row.remove();
  resetRow();
}

$(document).ready(function () {
  $('.hapus-btn').on('click', function(e) {
    e.preventDefault();
    var spb_id = $(this).attr('value');
    var delete_url = $(this).attr('data-url');
    $('#spb_id').val(spb_id);
    
    if (delete_url) {
      $('#deleteForm').attr('action', delete_url);
    } else {
      $('#deleteForm').attr('action', '/delete-spb/' + spb_id);
    }

    $('#deleteModal').modal('show');
  });
});