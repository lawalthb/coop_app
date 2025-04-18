<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $fillable = ['year'];

    public function entranceFees()
    {
        return $this->hasMany(EntranceFee::class);
    }

    public function commodityPayments()
{
    return $this->hasMany(CommodityPayment::class);
}

}
