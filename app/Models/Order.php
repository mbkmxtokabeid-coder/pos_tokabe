<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'order';
    protected $guarded = [];
    protected $fillable = [
        'order_by'
    ];
    public function penjualan()
    {
        return $this->hasMany(PenjualanTokabe::class, 'order_by');
    }
}
