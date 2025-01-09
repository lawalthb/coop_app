<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::where('is_admin', true)
            ->select('member_id', 'surname', 'firstname', 'email', 'role_id', 'created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Admin ID',
            'Surname',
            'First Name',
            'Email',
            'Role',
            'Join Date'
        ];
    }
}
