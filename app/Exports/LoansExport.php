<?php

namespace App\Exports;

use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoansExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Loan::with(['user', 'loanType'])
            ->get()
            ->map(function ($loan) {
                return [
                    'date' => $loan->created_at->format('Y-m-d'),
                    'reference' => $loan->reference,
                    'member' => $loan->user->surname . ' ' . $loan->user->firstname,
                    'loan_type' => $loan->loanType->name,
                    'amount' => $loan->amount,
                    'balance' => $loan->balance,
                    'status' => $loan->status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Reference',
            'Member',
            'Loan Type',
            'Amount',
            'Balance',
            'Status'
        ];
    }
}
