<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Transaction::with('user')
            ->get()
            ->map(function ($transaction) {
                return [
                    'date' => $transaction->created_at->format('Y-m-d'),
                    'member' => $transaction->user->surname . ' ' . $transaction->user->firstname,
                    'type' => $transaction->type,
                    'credit' => $transaction->credit_amount,
                    'debit' => $transaction->debit_amount,
                    'description' => $transaction->description,
                    'status' => $transaction->status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Member',
            'Type',
            'Credit',
            'Debit',
            'Description',
            'Status'
        ];
    }
}
