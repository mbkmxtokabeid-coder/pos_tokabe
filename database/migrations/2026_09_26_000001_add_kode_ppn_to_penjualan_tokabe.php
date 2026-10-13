<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('penjualan_tokabe', 'kode_ppn')) {
            Schema::table('penjualan_tokabe', function (Blueprint $table) {
                $table->string('kode_ppn', 10)->nullable()->default('01')->after('ppn');
            });

            // Backfill existing invoices with PPN based on customer / agency name
            $invoices = DB::table('penjualan_tokabe')->where('ppn', '>', 0)->get();
            foreach ($invoices as $inv) {
                $cust = strtolower(($inv->customer ?? '') . ' ' . ($inv->perusahaan ?? ''));
                $kode = '01';
                if (preg_match('/imigrasi|bpn|kantor|dinas|kementerian|pemko|pemkab/i', $cust)) {
                    $kode = '02';
                } elseif (preg_match('/bank|mandiri|bni|bri|btn|syariah|telkom|angkasa pura|injourney|jasa raharja|pln|pertamina|sumut/i', $cust)) {
                    $kode = '03';
                }
                DB::table('penjualan_tokabe')->where('id', $inv->id)->update(['kode_ppn' => $kode]);
            }
        }
    }

    public function down()
    {
        if (Schema::hasColumn('penjualan_tokabe', 'kode_ppn')) {
            Schema::table('penjualan_tokabe', function (Blueprint $table) {
                $table->dropColumn('kode_ppn');
            });
        }
    }
};
