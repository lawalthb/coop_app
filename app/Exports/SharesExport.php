<?php

namespace App\Exports;

use App\Models\Share;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SharesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Share::with(['user', 'shareType'])
            ->get()
            ->map(function ($share) {
                return [
                    'date' => $share->created_at->format('Y-m-d'),
                    'certificate' => $share->certificate_number,
                    'member' => $share->user->surname . ' ' . $share->user->firstname,
                    'share_type' => $share->shareType->name,
                    'units' => $share->number_of_units,
                    'amount' => $share->amount_paid,
                    'status' => $share->status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Certificate',
            'Member',
            'Share Type',
            'Units',
            'Amount',
            'Status'
        ];
    }
}
