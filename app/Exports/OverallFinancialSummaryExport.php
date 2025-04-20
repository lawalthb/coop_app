<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class OverallFinancialSummaryExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $summary;
    protected $months;
    protected $year;

    public function __construct($summary, $months, $year)
    {
        $this->summary = $summary;
        $this->months = $months;
        $this->year = $year;
    }

    public function collection()
    {
        $data = new Collection();

        // Savings Row
        $savingsRow = ['Savings'];
        foreach ($this->months as $month) {
            $savingsRow[] = $this->summary['savings']['months'][$month->id] > 0
                ? $this->summary['savings']['months'][$month->id]
                : 0;
        }
        $savingsRow[] = $this->summary['savings']['total'];
        $data->push($savingsRow);

        // Loan Repayments Row
        $loansRow = ['Loan Repayments'];
        foreach ($this->months as $month) {
            $loansRow[] = $this->summary['loans']['months'][$month->id] > 0
                ? $this->summary['loans']['months'][$month->id]
                : 0;
        }
        $loansRow[] = $this->summary['loans']['total'];
        $data->push($loansRow);

        // Share Subscriptions Row
        $sharesRow = ['Share Subscriptions'];
        foreach ($this->months as $month) {
            $sharesRow[] = $this->summary['shares']['months'][$month->id] > 0
                ? $this->summary['shares']['months'][$month->id]
                : 0;
        }
        $sharesRow[] = $this->summary['shares']['total'];
        $data->push($sharesRow);

        // Commodity Payments Row
        $commoditiesRow = ['Commodity Payments'];
        foreach ($this->months as $month) {
            $commoditiesRow[] = $this->summary['commodities']['months'][$month->id] > 0
                ? $this->summary['commodities']['months'][$month->id]
                : 0;
        }
        $commoditiesRow[] = $this->summary['commodities']['total'];
        $data->push($commoditiesRow);

               // Total Row
        $totalRow = ['TOTAL'];
        foreach ($this->months as $month) {
            $monthTotal =
                $this->summary['savings']['months'][$month->id] +
                $this->summary['loans']['months'][$month->id] +
                $this->summary['shares']['months'][$month->id] +
                $this->summary['commodities']['months'][$month->id];

            $totalRow[] = $monthTotal;
        }

        $grandTotal =
            $this->summary['savings']['total'] +
            $this->summary['loans']['total'] +
            $this->summary['shares']['total'] +
            $this->summary['commodities']['total'];

        $totalRow[] = $grandTotal;
        $data->push($totalRow);

        return $data;
    }

    public function headings(): array
    {
        $headings = ['Category'];

        foreach ($this->months as $month) {
            $headings[] = $month->name;
        }

        $headings[] = 'Total';

        return $headings;
    }

    public function title(): string
    {
        return 'Overall Financial Summary ' . $this->year;
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E9ECEF',
                ],
            ],
        ]);

        // Style the total row
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A' . $lastRow . ':' . $sheet->getHighestColumn() . $lastRow)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'F8F9FA',
                ],
            ],
        ]);

        // Format currency columns
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        for ($col = 'B'; $col <= $highestColumn; $col++) {
            for ($row = 2; $row <= $highestRow; $row++) {
                $sheet->getStyle($col . $row)->getNumberFormat()->setFormatCode('₦#,##0.00');
            }
        }

        // Auto size columns
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

