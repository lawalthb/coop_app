<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class MemberFinancialSummaryExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $summary;
    protected $months;
    protected $year;
    protected $member;

    public function __construct($summary, $months, $year, User $member)
    {
        $this->summary = $summary;
        $this->months = $months;
        $this->year = $year;
        $this->member = $member;
    }

    public function collection()
    {
        $data = new Collection();

        // Member Info
        $data->push(['Member Information']);
        $data->push(['Name', $this->member->surname . ' ' . $this->member->firstname . ' ' . $this->member->othername]);
        $data->push(['Member No', $this->member->member_no]);
        $data->push(['Department', $this->member->department->name ?? 'N/A']);
        $data->push(['Faculty', $this->member->faculty->name ?? 'N/A']);
        $data->push(['Joined', $this->member->created_at->format('M d, Y')]);
        $data->push([]);

        // Savings Section
        $hasSavings = false;
        foreach($this->summary['savings'] as $typeData) {
            if($typeData['total'] > 0) {
                $hasSavings = true;
                break;
            }
        }

        if ($hasSavings) {
            $data->push(['Savings']);

            // Headings for savings
            $savingsHeadings = ['Saving Type'];
            foreach ($this->months as $month) {
                $savingsHeadings[] = $month->name;
            }
            $savingsHeadings[] = 'Total';
            $data->push($savingsHeadings);

            // Savings data
            $savingsTotal = 0;
            foreach($this->summary['savings'] as $typeId => $typeData) {
                if($typeData['total'] > 0) {
                    $savingsTotal += $typeData['total'];
                    $row = [$typeData['name']];

                    foreach($this->months as $month) {
                        $row[] = $typeData['months'][$month->id] > 0 ? $typeData['months'][$month->id] : 0;
                    }

                    $row[] = $typeData['total'];
                    $data->push($row);
                }
            }

            // Savings total row
            $totalRow = ['Total Savings'];
            foreach($this->months as $month) {
                $monthTotal = 0;
                foreach($this->summary['savings'] as $typeData) {
                    $monthTotal += $typeData['months'][$month->id];
                }
                $totalRow[] = $monthTotal;
            }
            $totalRow[] = $savingsTotal;
            $data->push($totalRow);

            $data->push([]);
        }

        // Loan Repayments Section
        $hasLoans = false;
        foreach($this->summary['loans'] as $loanData) {
            if($loanData['total'] > 0) {
                $hasLoans = true;
                break;
            }
        }

        if ($hasLoans) {
            $data->push(['Loan Repayments']);

            // Headings for loans
            $loansHeadings = ['Loan'];
            foreach ($this->months as $month) {
                $loansHeadings[] = $month->name;
            }
            $loansHeadings[] = 'Total';
            $data->push($loansHeadings);

            // Loans data
            $loansTotal = 0;
            foreach($this->summary['loans'] as $loanId => $loanData) {
                if($loanData['total'] > 0) {
                    $loansTotal += $loanData['total'];
                    $row = [$loanData['name']];

                    foreach($this->months as $month) {
                        $row[] = $loanData['months'][$month->id] > 0 ? $loanData['months'][$month->id] : 0;
                    }

                    $row[] = $loanData['total'];
                    $data->push($row);
                }
            }

            // Loans total row
            $totalRow = ['Total Loan Repayments'];
            foreach($this->months as $month) {
                $monthTotal = 0;
                foreach($this->summary['loans'] as $loanData) {
                    $monthTotal += $loanData['months'][$month->id];
                }
                $totalRow[] = $monthTotal;
            }
            $totalRow[] = $loansTotal;
            $data->push($totalRow);

            $data->push([]);
        }

        // Share Subscriptions Section
        if ($this->summary['shares']['total'] > 0) {
            $data->push(['Share Subscriptions']);

            // Headings for shares
            $sharesHeadings = ['Type'];
            foreach ($this->months as $month) {
                $sharesHeadings[] = $month->name;
            }
            $sharesHeadings[] = 'Total';
            $data->push($sharesHeadings);

            // Shares data
            $row = [$this->summary['shares']['name']];
            foreach($this->months as $month) {
                $row[] = $this->summary['shares']['months'][$month->id] > 0 ? $this->summary['shares']['months'][$month->id] : 0;
            }
            $row[] = $this->summary['shares']['total'];
            $data->push($row);

            $data->push([]);
        }

        // Commodity Payments Section
        $hasCommodities = false;
        foreach($this->summary['commodities'] as $commodityData) {
            if($commodityData['total'] > 0) {
                $hasCommodities = true;
                break;
            }
        }

        if ($hasCommodities) {
            $data->push(['Commodity Payments']);

            // Headings for commodities
            $commoditiesHeadings = ['Commodity'];
            foreach ($this->months as $month) {
                $commoditiesHeadings[] = $month->name;
            }
            $commoditiesHeadings[] = 'Total';
            $data->push($commoditiesHeadings);

            // Commodities data
            $commoditiesTotal = 0;
            foreach($this->summary['commodities'] as $subscriptionId => $commodityData) {
                if($commodityData['total'] > 0) {
                    $commoditiesTotal += $commodityData['total'];
                    $row = [$commodityData['name']];

                    foreach($this->months as $month) {
                        $row[] = $commodityData['months'][$month->id] > 0 ? $commodityData['months'][$month->id] : 0;
                    }

                    $row[] = $commodityData['total'];
                    $data->push($row);
                }
            }

            // Commodities total row
            $totalRow = ['Total Commodity Payments'];
            foreach($this->months as $month) {
                $monthTotal = 0;
                foreach($this->summary['commodities'] as $commodityData) {
                    $monthTotal += $commodityData['months'][$month->id];
                }
                $totalRow[] = $monthTotal;
            }
            $totalRow[] = $commoditiesTotal;
            $data->push($totalRow);

            $data->push([]);
        }

        // Grand Total Section
        $data->push(['Grand Total']);

        // Headings for grand total
        $grandTotalHeadings = ['Category'];
        foreach ($this->months as $month) {
            $grandTotalHeadings[] = $month->name;
        }
        $grandTotalHeadings[] = 'Total';
        $data->push($grandTotalHeadings);

        // Grand total row
        $totalRow = ['GRAND TOTAL'];
        foreach($this->months as $month) {
            $monthTotal = 0;

            // Add savings
            foreach($this->summary['savings'] as $typeData) {
                $monthTotal += $typeData['months'][$month->id];
            }

            // Add loan repayments
            foreach($this->summary['loans'] as $loanData) {
                $monthTotal += $loanData['months'][$month->id];
            }

            // Add share subscriptions
            $monthTotal += $this->summary['shares']['months'][$month->id];

            // Add commodity payments
            foreach($this->summary['commodities'] as $commodityData) {
                $monthTotal += $commodityData['months'][$month->id];
            }

            $totalRow[] = $monthTotal;
        }

        $grandTotal = 0;

        // Add savings total
        foreach($this->summary['savings'] as $typeData) {
            $grandTotal += $typeData['total'];
        }

        // Add loan repayments total
        foreach($this->summary['loans'] as $loanData) {
            $grandTotal += $loanData['total'];
        }

        // Add share subscriptions total
        $grandTotal += $this->summary['shares']['total'];

        // Add commodity payments total
        foreach($this->summary['commodities'] as $commodityData) {
            $grandTotal += $commodityData['total'];
        }

        $totalRow[] = $grandTotal;
        $data->push($totalRow);

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Member Financial Summary ' . $this->year;
    }

    public function styles(Worksheet $sheet)
    {
        // Format currency cells
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Find section headers and apply styles
        for ($row = 1; $row <= $highestRow; $row++) {
            $value = $sheet->getCell('A' . $row)->getValue();

            if (in_array($value, ['Savings', 'Loan Repayments', 'Share Subscriptions', 'Commodity Payments', 'Grand Total', 'Member Information'])) {
                // Style section headers
                $sheet->getStyle('A' . $row)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'E9ECEF',
                        ],
                    ],
                ]);

                // If this is a section header, the next row is likely column headers
                if ($row < $highestRow && in_array($value, ['Savings', 'Loan Repayments', 'Share Subscriptions', 'Commodity Payments', 'Grand Total'])) {
                    $headerRow = $row + 1;
                    $sheet->getStyle('A' . $headerRow . ':' . $highestColumn . $headerRow)->applyFromArray([
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
                }

                // Find the total rows and style them
                if ($row < $highestRow - 1) {
                    for ($i = $row + 2; $i <= $highestRow; $i++) {
                        $cellValue = $sheet->getCell('A' . $i)->getValue();
                        if (strpos($cellValue, 'Total') === 0) {
                            $sheet->getStyle('A' . $i . ':' . $highestColumn . $i)->applyFromArray([
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
                        }
                    }
                }
            }
        }

        // Format currency values
        for ($row = 1; $row <= $highestRow; $row++) {
            $value = $sheet->getCell('A' . $row)->getValue();

            // Skip header rows and empty rows
            if (empty($value) || in_array($value, ['Savings', 'Loan Repayments', 'Share Subscriptions', 'Commodity Payments', 'Grand Total', 'Member Information'])) {
                continue;
            }

            // Check if this is a data row (has a category name in column A)
            if ($row > 8) { // Skip member info section
                for ($col = 'B'; $col <= $highestColumn; $col++) {
                    $cellValue = $sheet->getCell($col . $row)->getValue();
                    if (is_numeric($cellValue)) {
                        $sheet->getStyle($col . $row)->getNumberFormat()->setFormatCode('₦#,##0.00');
                    }
                }
            }
        }

        // Auto size columns
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }
}
