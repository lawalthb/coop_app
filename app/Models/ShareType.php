<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareType extends Model
{
    protected $fillable = [
        'name',
        'price_per_unit',
        'minimum_units',
        'maximum_units',
        'dividend_rate',
        'is_transferable',
        'has_voting_rights',
        'status',
        'description'
    ];

    public function shares()
    {
        return $this->hasMany(Share::class);
    }
}
