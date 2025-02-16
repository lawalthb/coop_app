<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    protected $fillable = ['name'];

    public function entranceFees()
    {
        return $this->hasMany(EntranceFee::class);
    }

    public function shares()
    {
        return $this->hasMany(Share::class);
    }
}
