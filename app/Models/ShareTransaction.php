<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_type',
        'amount',
        'reference',
        'status',
        'remark',
        'posted_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
