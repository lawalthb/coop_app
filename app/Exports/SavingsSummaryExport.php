<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SavingsSummaryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = User::where('is_admin', false)
            ->when($this->filters['status'] ?? null, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($this->filters['search'] ?? null, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('member_no', 'like', "%{$search}%")
                      ->orWhere('firstname', 'like', "%{$search}%")
                      ->orWhere('surname', 'like', "%{$search}%");
                });
            });

        return $query->withSum('savings', 'amount')
                    ->withSum(['withdrawals' => function($query) {
                        $query->where('status', 'approved');
                    }], 'amount')
                    ->get();
    }

    public function headings(): array
    {
        return [
            'S/N',
            'Member No',
            'Title',
            'First Name',
            'Surname',
            'Email',
            'Phone Number',
            'Total Saved (₦)',
            'Total Withdrawn (₦)',
            'Balance (₦)',
            'Status',
            'Date Joined'
        ];
    }

    public function map($member): array
    {
        static $counter = 0;
        $counter++;

        $totalSaved = $member->savings_sum_amount ?? 0;
        $totalWithdrawn = $member->withdrawals_sum_amount ?? 0;
        $balance = $totalSaved - $totalWithdrawn;

        return [
            $counter,
            $member->member_no,
            $member->title,
            $member->firstname,
            $member->surname,
            $member->email,
            $member->phone_number,
            number_format($totalSaved, 2),
            number_format($totalWithdrawn, 2),
            number_format($balance, 2),
            ucfirst($member->status),
            $member->created_at->format('M d, Y')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '7C3AED'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
            // Style all data rows
            'A:L' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // Style amount columns
            'H:J' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
                'numberFormat' => [
                    'formatCode' => '#,##0.00',
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Savings Summary';
    }
}
