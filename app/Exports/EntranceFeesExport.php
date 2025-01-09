<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EntranceFeesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Transaction::where('type', 'entrance_fee')
            ->with('user')
            ->get()
            ->map(function ($transaction) {
                return [
                    'date' => $transaction->created_at->format('Y-m-d'),
                    'member' => $transaction->user->surname . ' ' . $transaction->user->firstname,
                    'amount' => $transaction->credit_amount,
                    'status' => $transaction->status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Member',
            'Amount',
            'Status'
        ];
    }
}
