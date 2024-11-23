<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'title',
        'firstname',
        'surname',
        'othername',
        'email',
        'password',
        'phone_number',
        'staff_no',
        'dob',
        'nationality',
        'home_address',
        'state_id',
        'lga_id',
        'faculty_id',
        'department_id',
        'nok',
        'nok_relationship',
        'nok_phone',
        'nok_address',
        'monthly_savings',
        'share_subscription',
        'month_commence',
        'member_image',
        'signature_image',
        'admin_sign',
        'status',
        'is_admin',
        'member_no',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

public function notifications()
{
    return $this->hasMany(Notification::class);
}

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }



}
