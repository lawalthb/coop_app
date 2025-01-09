<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $fillable = [
        'user_id',
        'share_type_id',
        'certificate_number',
        'number_of_units',
        'amount_paid',
        'unit_price',
        'status',
        'approved_by',
        'approved_at',
        'posted_by',
        'remark'
    ];

    protected $casts = [
        'approved_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shareType()
    {
        return $this->belongsTo(ShareType::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
