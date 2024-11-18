<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'title', 'surname', 'firstname', 'othername', 'home_address',
        'department_id', 'faculty_id', 'phone_number', 'email', 'dob',
        'nationality', 'state_id', 'lga_id', 'nok', 'nok_relationship',
        'nok_address', 'marital_status', 'religion', 'nok_phone',
        'monthly_savings', 'share_subscription', 'month_commence',
        'staff_no', 'signature_image', 'date_join', 'admin_remark',
        'admin_sign', 'admin_signdate', 'member_no', 'gensec_sign_image',
        'president_sign', 'member_image', 'password', 'is_admin'
    ];

    protected $casts = [
        'dob' => 'date',
        'date_join' => 'date',
        'admin_signdate' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
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
