<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MembersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::where('is_admin', false)
            ->select('id', 'surname', 'firstname', 'email', 'is_approved', 'created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Member ID',
            'Surname',
            'First Name',
            'Email',
            'Status',
            'Join Date'
        ];
    }
}
