<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceApprovalRequest extends Model
{
    use HasFactory;

    protected $table = 'invoice_approval_requests';

    protected $fillable = [
        'invoice_id',
        'user_id',
        'tipe_request',
        'status',
        'catatan_admin',
        'catatan_owner',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(PenjualanTokabe::class, 'invoice_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
