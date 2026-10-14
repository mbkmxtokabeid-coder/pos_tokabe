<?php

use App\Exports\LaporanExport;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\HomeTKBController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenjualanTokabeController;
use App\Http\Controllers\SpbController;
use App\Http\Controllers\SpjController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\LaporanPembelianController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TokenController;
use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::redirect('/', '/welcomeTKB');

Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['status']], function () {

        // ====================================================================
        // ROUTE KHUSUS PEMILIK
        // ====================================================================
        Route::group(['middleware' => ['pemilik']], function () {
            Route::get('/add-user', [UserController::class, 'addUser'])->name('user.add');
            Route::post('/post-user', [UserController::class, 'storeUser'])->name('user.store');
            Route::get('/daftar-user', [UserController::class, 'index'])->name('user.index');
            Route::get('/status-user/{id}', [UserController::class, 'status'])->name('user.status');
            Route::get('/edit-user/{id}', [UserController::class, 'edit'])->name('user.edit');
            Route::put('/update-user/{id}', [UserController::class, 'update'])->name('user.update');
            
            // ROUTE BUDGET
            Route::get('/tambah-budget', [BudgetController::class, 'tambah'])->name('budget.add');
            Route::get('/daftar-budget', [BudgetController::class, 'index'])->name('budget.list');
            Route::post('/store-budget', [BudgetController::class, 'store'])->name('budget.store');
            Route::get('/edit-budget/{id}', [BudgetController::class, 'edit'])->name('budget.edit');
            Route::put('/update-budget/{id}', [BudgetController::class, 'update'])->name('budget.update');
            Route::delete('/delete-budget/{id}', [BudgetController::class, 'delete'])->name('budget.delete');
            
            // ROUTE REKENING BANK (KHUSUS PEMILIK)
            Route::get('/daftar-rekening', [RekeningController::class, 'index'])->name('rekening.index');
            Route::get('/tambah-rekening', [RekeningController::class, 'create'])->name('rekening.create');
            Route::post('/store-rekening', [RekeningController::class, 'store'])->name('rekening.store');
            Route::get('/edit-rekening/{id}', [RekeningController::class, 'edit'])->name('rekening.edit');
            Route::put('/update-rekening/{id}', [RekeningController::class, 'update'])->name('rekening.update');
            Route::delete('/delete-rekening/{id}', [RekeningController::class, 'destroy'])->name('rekening.destroy');
            Route::get('/status-rekening/{id}', [RekeningController::class, 'toggleStatus'])->name('rekening.status');

        });
        
        // ====================================================================
        // ROUTE ADMIN
        // ====================================================================
        Route::group(['middleware' => ['admin']], function () {
            Route::get('/items', [ItemController::class, 'index'])->name('item.index');
            Route::post('/items', [ItemController::class, 'store'])->name('item.store');
            Route::put('/items/{item}', [ItemController::class, 'update'])->name('item.update');
            Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('item.destroy');

            // TOKABE
            Route::get('/list-invoice/tokabe', [PenjualanTokabeController::class, 'listInvoiceTokabe'])->name('list_invoice_tokabe');
            Route::get('/list-invoice/tokabe/cards-data', [PenjualanTokabeController::class, 'getCardsData'])->name('invoice.tokabe.cards_data');
            Route::get('/addInvoiceTokabe', [PenjualanTokabeController::class, 'addInvoice'])->name('add.inv.tkb');
            Route::post('/addInvoiceTokabe', [PenjualanTokabeController::class, 'storeInvoiceTokabe'])->name('storeInvoice.Tokabe');
            Route::delete('/deleteInvoiceTokabe/{id}', [PenjualanTokabeController::class, 'delete'])->name('delete.invoiceTokabe');
            Route::get('editInvoiceTokabe/{id}/edit', [PenjualanTokabeController::class, 'editInvoice'])->name('edit.inv.tkb');
            Route::put('update-InvoiceTokabe/{id}', [PenjualanTokabeController::class, 'updateInvoice'])->name('update-invoiceTokabe');
            Route::get('/ubah-status/{status}/{id}', [PenjualanTokabeController::class, 'ubahStatus'])->name('ubahStatus.tkb');
            Route::get('/cetakTKB/{id}', [PenjualanTokabeController::class, 'cetakInvoiceTKB'])->name('cetak.inv.tkb');
            Route::get('/get-invoice-number', [PenjualanTokabeController::class, 'getUpdatedInvoiceNumber']);

            // TOKABE APPROVAL & PELUNASAN
            Route::post('/ubah-status-unlock-tokabe/{id}', [PenjualanTokabeController::class, 'unlock'])->name('tokabe.unlock');
            Route::get('/ubah-status-lock-tokabe/{id}', [PenjualanTokabeController::class, 'lock'])->name('tokabe.lock');
            Route::get('/tokabe-approval/{id}', [PenjualanTokabeController::class, 'approvalPage'])->middleware('role:Pemilik')->name('tokabe.approval.page');
            Route::get('/tokabe-pelunasan/{id}', [PenjualanTokabeController::class, 'pelunasanPage'])->middleware('role:Pemilik')->name('tokabe.pelunasan.page');
            Route::get('/tokabe-batal/{id}', [PenjualanTokabeController::class, 'batalPage'])->middleware('role:Pemilik')->name('tokabe.batal.page');
            Route::get('/tokabe-delete/{id}', [PenjualanTokabeController::class, 'deletePage'])->middleware('role:Pemilik')->name('tokabe.delete.page');
            Route::post('/ubah-status-batal-tokabe/{id}', [PenjualanTokabeController::class, 'storeStatusBatal'])->name('tokabe.status.batal');
            Route::post('/ubah-status-lunas-tokabe/{id}', [PenjualanTokabeController::class, 'statusLunas'])->name('tokabe.status.lunas');

            Route::get('/getInvoices/{id}', [CustomerController::class, 'getInvoices']);

            // ROUTE SPB
            Route::get('/daftar-spb', [SpbController::class, 'indexSPB'])->name('daftar-spb');
            Route::get('/cetak-spb/{id}', [SpbController::class, 'cetakSPB']);
            Route::get('/tambah-spb', [SpbController::class, 'tambahSPB']);
            Route::post('/store-spb', [SpbController::class, 'storeSPB']);
            Route::get('/edit-spb/{id}', [SpbController::class, 'editSPB']);
            Route::put('/update-spb/{id}', [SpbController::class, 'updateSPB']);
            Route::delete('/delete-spb/{id}', [SpbController::class, 'destroySPB']);
            Route::get('/ubah-status/{id}', [SpbController::class, 'ubahStatus'])->name('spb.ubah-status');

            // ROUTE PEMBELIAN (terpisah dari SPB)
            Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
            Route::get('/pembelian/vendor/{id}/tambah', [PembelianController::class, 'create'])->name('pembelian.create');
            Route::post('/pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
            Route::get('/pembelian/{id}/edit', [PembelianController::class, 'edit'])->name('pembelian.edit');
            Route::put('/pembelian/{id}', [PembelianController::class, 'update'])->name('pembelian.update');
            Route::delete('/pembelian/{id}', [PembelianController::class, 'destroy'])->name('pembelian.destroy');
            Route::get('/pembelian/{id}/lunas', [PembelianController::class, 'lunas'])->name('pembelian.lunas');
            // APPROVAL PEMBELIAN (LOCK / UNLOCK)
            Route::post('/ubah-status-unlock-pembelian/{id}', [PembelianController::class, 'unlock'])->name('pembelian.unlock');
            Route::get('/ubah-status-lock-pembelian/{id}', [PembelianController::class, 'lock'])->name('pembelian.lock');
            Route::get('/pembelian-approval/{id}', [PembelianController::class, 'approvalPage'])->middleware('role:Pemilik')->name('pembelian.approval.page');
            Route::post('/pembelian/vendor', [PembelianController::class, 'storeVendor'])->name('pembelian.vendor.store');
            Route::put('/pembelian/vendor/{id}', [PembelianController::class, 'updateVendor'])->name('pembelian.vendor.update');
            Route::get('/pembelian/vendor/{id}', [PembelianController::class, 'showVendor'])->name('pembelian.vendor.show');
            Route::delete('/pembelian/vendor/{id}', [PembelianController::class, 'destroyVendor'])->name('pembelian.vendor.destroy');
            Route::get('/laporan-pembelian', [LaporanPembelianController::class, 'index'])->name('laporan.pembelian');
            Route::get('/laporan-pembelian/filter', [LaporanPembelianController::class, 'index'])->name('laporan.pembelian.filter');

            // ROUTE SPJ
            Route::get('/daftar-spj', [SpjController::class, 'index'])->name('spj.index');
            Route::get('/tambah-spj', [SpjController::class, 'create']);
            Route::get('/generate-spj-number', [SpjController::class, 'generateNumber']);
            Route::post('/store-spj', [SpjController::class, 'store']);
            Route::get('/cetak-spj/{id}', [SpjController::class, 'cetak']);
            Route::get('/lihat-spj/{id}', [SpjController::class, 'lihat']);
            Route::delete('/delete-spj/{id}', [SpjController::class, 'destroy']);
            Route::put('/update-spj/{id}', [SpjController::class, 'update']);
            Route::get('/edit-spj/{id}', [SpjController::class, 'edit']);

            // ROUTE BARANG
            Route::get('listBarang', [BarangController::class, 'index'])->name('listBarang');
            Route::get('addBarang', [BarangController::class, 'create']);
            Route::post('barang', [BarangController::class, 'store'])->name('barang.store');
            Route::delete('deleteBarang/{id}', [BarangController::class, 'destroy']);
            Route::get('/edit-stok/{id}', [BarangController::class, 'edit']);
            Route::put('/update-stok/{id}', [BarangController::class, 'update']);
                                    
            

            // ROUTE DAFTAR CUSTOMER
            Route::get('/daftar-pelanggan', [CustomerController::class, 'index'])->name('daftar.pelanggan');
            Route::get('/daftar-customer', [CustomerController::class, 'index'])->name('daftar.customer');
            Route::get('/daftar-costumer', [CustomerController::class, 'index']);
            Route::get('/customer', [CustomerController::class, 'index']);
            Route::get('/costumer', [CustomerController::class, 'index']);
            Route::post('/pdf-pelanggan', [CustomerController::class, 'show'])->name('export.pelanggan');

            // ROUTE LAPORAN
            Route::get('/daftar-laporan', [LaporanController::class, 'index']);
            Route::post('/laporan-export', [LaporanController::class, 'exportByInvoice']);
            Route::post('/laporan-export-date', [LaporanController::class, 'exportByDate']);
            Route::post('/laporan-export-month', [LaporanController::class, 'exportByMonth']);
            Route::post('/laporan-export-year', [LaporanController::class, 'exportByYear']);
            Route::post('/laporan-penjualan-barang-export', [LaporanController::class, 'exportByBarang'])->name('export.barang');
            Route::post('/invoice-export', [LaporanController::class, 'exportInvoice'])->name('export.invoice');

            // ALIAS ROUTE LAPORAN DENGAN PREFIX /invoice
            Route::post('/invoice/laporan-export', [LaporanController::class, 'exportByInvoice']);
            Route::post('/invoice/laporan-export-date', [LaporanController::class, 'exportByDate']);
            Route::post('/invoice/laporan-export-month', [LaporanController::class, 'exportByMonth']);
            Route::post('/invoice/laporan-export-year', [LaporanController::class, 'exportByYear']);
            Route::get('/invoice/getInvoices/{id}', [CustomerController::class, 'getInvoices']);


            // ROUTE TOKABE DASHBOARD
            Route::get('/welcomeTKB', [HomeTKBController::class, 'index']);
            Route::get('/dashboard-tkb/barang-chart-data', [HomeTKBController::class, 'getBarangDataAjax'])->name('dashboard.barang.chart.data');
            Route::get('/dashboard-tkb/payment-chart-data', [HomeTKBController::class, 'getPaymentChartDataAjax'])->name('dashboard.payment.chart.data');

        });

        // ====================================================================
        // ROUTE UMUM (PROFILE & JSON)
        // ====================================================================
        Route::get('/profile', [UserController::class, 'profiles'])->name('auth.profile');
        Route::put('/profile/update/{id}', [UserController::class, 'updateProfile'])->name('user.profile.update');
        
        Route::get('invoice/get-barang-data/{kategoriId}', [BarangController::class, 'dataBarang']);
        Route::get('invoice/get-potensi-profit/{kategoriId}', [BarangController::class, 'potensiProfit']);
    
    });
    
    Route::post('/token-notif', TokenController::class)->name('token');
});

// ROUTE JSON
Route::get('/jenis-barang', function () {
    $jenis = Barang::all();
    return response()->json($jenis);
});
Route::get('/kategori-barang', function () {
    $jenis = KategoriBarang::all();
    return response()->json($jenis);
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// ROUTE PUBLIC DOWNLOAD (tanpa login)
Route::get('/view/download/invoiceTKB/{id}', [PenjualanTokabeController::class, 'viewDownloadInvoiceTKB'])->name('download.inv.tkb');

// ROUTE PUBLIC DOWNLOAD TOKABE (BERLAKU 7 HARI)
Route::get('/invoice-tkb/public-download/{id}', [PenjualanTokabeController::class, 'publicDownloadTokabe'])
    ->name('invoice_tkb.public_download')
    ->middleware('signed');
 
// TEST ROUTE
Route::get('/test', function () {
    return view('pages.laporan.pdf');
});