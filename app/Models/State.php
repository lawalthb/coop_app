<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = ['name', 'status'];

    public function lgas()
    {
        return $this->hasMany(Lga::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
