<?php

namespace App\Exports;

use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LoanRepaymentTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        $loans = Loan::with(['user', 'loanType'])
            ->whereIn('status', ['approved', 'active', 'disbursed'])
            ->where('balance', '>', 0)
            ->get();

        $data = [];

        foreach ($loans as $loan) {
            $data[] = [
                $loan->user->email,
                $loan->reference,
                $loan->monthly_payment,
                date('n'), // Current month number
                date('Y')  // Current year
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Email',
            'Loan Reference',
            'Monthly Amount',
            'Month ID',
            'Year ID'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}