<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntranceFee extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'month_id',
        'year_id',
        'remark',
        'posted_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function month()
    {
        return $this->belongsTo(Month::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
