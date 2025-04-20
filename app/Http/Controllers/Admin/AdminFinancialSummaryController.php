<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommodityPayment;
use App\Models\CommoditySubscription;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\Month;
use App\Models\Saving;
use App\Models\SavingType;
use App\Models\Share;
use App\Models\User;
use App\Models\Year;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminFinancialSummaryController extends Controller
{
  public function index(Request $request)
{
    $selectedYear = $request->input('year', date('Y'));
    $selectedMemberId = $request->input('member_id');

    $years = Year::orderBy('year', 'desc')->get();
    $months = Month::all();
    $members = User::where('is_admin', 0)->orderBy('surname')->get();

    // If a specific member is selected
    if ($selectedMemberId) {
        return redirect()->route('admin.financial-summary.member', [
            'member' => $selectedMemberId,
            'year' => $selectedYear
        ]);
    }

    // Otherwise, show the overall summary for all members
    $summary = $this->getOverallSummaryData($selectedYear);

    return view('admin.financial-summary.index', compact(
        'summary', 'months', 'years', 'selectedYear', 'members'
    ));
}


    public function exportMember(Request $request)
    {
        $selectedYear = $request->input('year', date('Y'));
        $selectedMemberId = $request->input('member_id');

        if (!$selectedMemberId) {
            return redirect()->route('admin.financial-summary.index')
                ->with('error', 'No member selected for export');
        }

        $member = User::findOrFail($selectedMemberId);
        $summary = $this->getMemberSummaryData($member, $selectedYear);
        $months = Month::all();

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Financial Summary for ' . $selectedYear);
        $sheet->setCellValue('A2', 'Member: ' . $member->surname . ' ' . $member->firstname . ' (' . $member->member_no . ')');
        $sheet->setCellValue('A3', 'Generated on: ' . now()->format('M d, Y'));

        // Set column headers for months
        $sheet->setCellValue('A5', 'Category');
        $col = 'B';
        foreach ($months as $month) {
            $sheet->setCellValue($col . '5', $month->name);
            $col++;
        }
        $sheet->setCellValue($col . '5', 'Total');

        // Add savings data
        $row = 6;
        $sheet->setCellValue('A' . $row, 'SAVINGS');
        $row++;

        foreach ($summary['savings'] as $typeId => $data) {
            if ($data['total'] > 0) {
                $sheet->setCellValue('A' . $row, $data['name']);
                $col = 'B';
                foreach ($months as $month) {
                    $sheet->setCellValue($col . $row, $data['months'][$month->id]);
                    $col++;
                }
                $sheet->setCellValue($col . $row, $data['total']);
                $row++;
            }
        }

        // Add loan repayments data
        $row++;
        $sheet->setCellValue('A' . $row, 'LOAN REPAYMENTS');
        $row++;

        foreach ($summary['loans'] as $loanId => $data) {
            if ($data['total'] > 0) {
                $sheet->setCellValue('A' . $row, $data['name']);
                $col = 'B';
                foreach ($months as $month) {
                    $sheet->setCellValue($col . $row, $data['months'][$month->id]);
                    $col++;
                }
                $sheet->setCellValue($col . $row, $data['total']);
                $row++;
            }
        }

        // Add shares data
        $row++;
        $sheet->setCellValue('A' . $row, 'SHARE SUBSCRIPTIONS');
        $row++;

        if ($summary['shares']['total'] > 0) {
            $sheet->setCellValue('A' . $row, $summary['shares']['name']);
            $col = 'B';
            foreach ($months as $month) {
                $sheet->setCellValue($col . $row, $summary['shares']['months'][$month->id]);
                $col++;
            }
            $sheet->setCellValue($col . $row, $summary['shares']['total']);
            $row++;
        }

        // Add commodity payments data
        $row++;
        $sheet->setCellValue('A' . $row, 'COMMODITY PAYMENTS');
        $row++;

        foreach ($summary['commodities'] as $subscriptionId => $data) {
            if ($data['total'] > 0) {
                $sheet->setCellValue('A' . $row, $data['name']);
                $col = 'B';
                foreach ($months as $month) {
                    $sheet->setCellValue($col . $row, $data['months'][$month->id]);
                    $col++;
                }
                $sheet->setCellValue($col . $row, $data['total']);
                $row++;
            }
        }

        // Format the spreadsheet
        $spreadsheet->getActiveSheet()->getStyle('A1:A3')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A5:' . $col . '5')->getFont()->setBold(true);

        // Create the file
        $writer = new Xlsx($spreadsheet);
        $filename = 'financial_summary_' . $selectedYear . '_' . $member->member_no . '.xlsx';

        // Save to temp file and return for download
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function downloadMemberPdf(Request $request)
    {
        $selectedYear = $request->input('year', date('Y'));
        $selectedMemberId = $request->input('member_id');

        if (!$selectedMemberId) {
            return redirect()->route('admin.financial-summary.index')
                ->with('error', 'No member selected for PDF');
        }

        $member = User::findOrFail($selectedMemberId);
        $summary = $this->getMemberSummaryData($member, $selectedYear);
        $months = Month::all();
        $years = Year::orderBy('year', 'desc')->get();

        $pdf = PDF::loadView('admin.financial-summary.member-pdf', compact(
            'summary', 'months', 'years', 'selectedYear', 'member'
        ));

        return $pdf->download('financial_summary_' . $selectedYear . '_' . $member->member_no . '.pdf');
    }

    public function exportOverall(Request $request)
    {
        $selectedYear = $request->input('year', date('Y'));
        $summary = $this->getOverallSummaryData($selectedYear);
        $months = Month::all();

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Overall Financial Summary for ' . $selectedYear);
        $sheet->setCellValue('A2', 'OGITECH Cooperative Society');
        $sheet->setCellValue('A3', 'Generated on: ' . now()->format('M d, Y'));

        // Set column headers for months
        $sheet->setCellValue('A5', 'Category');
        $col = 'B';
        foreach ($months as $month) {
            $sheet->setCellValue($col . '5', $month->name);
            $col++;
        }
        $sheet->setCellValue($col . '5', 'Total');

        // Add data for each category
        $row = 6;

        // Savings
        $sheet->setCellValue('A' . $row, 'Savings');
        $col = 'B';
        foreach ($months as $month) {
            $sheet->setCellValue($col . $row, $summary['savings']['months'][$month->id]);
            $col++;
        }
        $sheet->setCellValue($col . $row, $summary['savings']['total']);
        $row++;

        // Loan Repayments
        $sheet->setCellValue('A' . $row, 'Loan Repayments');
        $col = 'B';
        foreach ($months as $month) {
            $sheet->setCellValue($col . $row, $summary['loans']['months'][$month->id]);
            $col++;
        }
        $sheet->setCellValue($col . $row, $summary['loans']['total']);
        $row++;

        // Share Subscriptions
        $sheet->setCellValue('A' . $row, 'Share Subscriptions');
        $col = 'B';
        foreach ($months as $month) {
            $sheet->setCellValue($col . $row, $summary['shares']['months'][$month->id]);
            $col++;
        }
        $sheet->setCellValue($col . $row, $summary['shares']['total']);
        $row++;

        // Commodity Payments
        $sheet->setCellValue('A' . $row, 'Commodity Payments');
        $col = 'B';
        foreach ($months as $month) {
            $sheet->setCellValue($col . $row, $summary['commodities']['months'][$month->id]);
            $col++;
        }
        $sheet->setCellValue($col . $row, $summary['commodities']['total']);
        $row++;

        // Grand Total
        $row++;
        $sheet->setCellValue('A' . $row, 'GRAND TOTAL');
        $col = 'B';
        foreach ($months as $month) {
            $monthTotal =
                $summary['savings']['months'][$month->id] +
                $summary['loans']['months'][$month->id] +
                $summary['shares']['months'][$month->id] +
                $summary['commodities']['months'][$month->id];

            $sheet->setCellValue($col . $row, $monthTotal);
            $col++;
        }
        $sheet->setCellValue($col . $row,
            $summary['savings']['total'] +
            $summary['loans']['total'] +
            $summary['shares']['total'] +
            $summary['commodities']['total']
        );

        // Format the spreadsheet
        $spreadsheet->getActiveSheet()->getStyle('A1:A3')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A5:' . $col . '5')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':' . $col . $row)->getFont()->setBold(true);

        // Create the file
        $writer = new Xlsx($spreadsheet);
        $filename = 'overall_financial_summary_' . $selectedYear . '.xlsx';

        // Save to temp file and return for download
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function downloadOverallPdf(Request $request)
    {
        $selectedYear = $request->input('year', date('Y'));
        $summary = $this->getOverallSummaryData($selectedYear);
        $months = Month::all();

        $pdf = PDF::loadView('admin.financial-summary.overall-pdf', compact(
            'summary', 'months', 'selectedYear'
        ));

        return $pdf->download('overall_financial_summary_' . $selectedYear . '.pdf');
    }

    private function getMemberSummaryData($member, $selectedYear)
    {
        // Initialize summary array
        $summary = [
            'savings' => [],
            'loans' => [],
            'shares' => [
                'name' => 'Share Subscriptions',
                'months' => [],
                'total' => 0
            ],
            'commodities' => []
        ];

        $months = Month::all();
        $yearRecord = Year::where('year', $selectedYear)->first();

        if (!$yearRecord) {
            return $summary;
        }

        // Initialize months for shares
        foreach ($months as $month) {
            $summary['shares']['months'][$month->id] = 0;
        }

        // Get savings summary
        $savingTypes = SavingType::all();

        foreach ($savingTypes as $type) {
            $summary['savings'][$type->id] = [
                'name' => $type->name,
                'months' => [],
                'total' => 0
            ];

            foreach ($months as $month) {
                $summary['savings'][$type->id]['months'][$month->id] = 0;
            }
        }

        $savings = Saving::where('user_id', $member->id)
            ->where('year_id', $yearRecord->id)
            ->where('status', 'completed')
            ->get();

        foreach ($savings as $saving) {
            if (isset($summary['savings'][$saving->saving_type_id])) {
                $summary['savings'][$saving->saving_type_id]['months'][$saving->month_id] += $saving->amount;
                $summary['savings'][$saving->saving_type_id]['total'] += $saving->amount;
            }
        }

        // Get loan repayments summary
        $loans = Loan::where('user_id', $member->id)
            ->where('status', 'approved')
            ->get();

        foreach ($loans as $loan) {
                    $summary['loans'][$loan->id] = [
                        'name' => $loan->loanType->name . ' (' . $loan->reference . ')',
                        'months' => [],
                        'total' => 0
                    ];            $summary['loans'][$loan->id] = [
                'name' => $loan->loanType->name . ' (' . $loan->reference . ')',
                'months' => [],
                'total' => 0
            ];

            foreach ($months as $month) {
                $summary['loans'][$loan->id]['months'][$month->id] = 0;
            }
        }

        $repayments = LoanRepayment::whereIn('loan_id', $loans->pluck('id'))
            ->where('year_id', $yearRecord->id)
            ->get();

        foreach ($repayments as $repayment) {
            if (isset($summary['loans'][$repayment->loan_id])) {
                $summary['loans'][$repayment->loan_id]['months'][$repayment->month_id] += $repayment->amount;
                $summary['loans'][$repayment->loan_id]['total'] += $repayment->amount;
            }
        }

        // Get shares summary
        $shares = Share::where('user_id', $member->id)
            ->where('year_id', $yearRecord->id)
            ->where('status', 'completed')
            ->get();

        foreach ($shares as $share) {
            $summary['shares']['months'][$share->month_id] += $share->amount;
            $summary['shares']['total'] += $share->amount;
        }

        // Get commodity payments summary
        $subscriptions = CommoditySubscription::where('user_id', $member->id)
            ->where('status', 'approved')
            ->with('commodity')
            ->get();

        foreach ($subscriptions as $subscription) {
            $summary['commodities'][$subscription->id] = [
                'name' => $subscription->commodity->name . ' (' . $subscription->reference . ')',
                'months' => [],
                'total' => 0
            ];

            foreach ($months as $month) {
                $summary['commodities'][$subscription->id]['months'][$month->id] = 0;
            }
        }

        $payments = CommodityPayment::whereIn('commodity_subscription_id', $subscriptions->pluck('id'))
            ->where('status', 'approved')
            ->get();

        foreach ($payments as $payment) {
            if (isset($summary['commodities'][$payment->commodity_subscription_id])) {
                $summary['commodities'][$payment->commodity_subscription_id]['months'][$payment->month_id] += $payment->amount;
                $summary['commodities'][$payment->commodity_subscription_id]['total'] += $payment->amount;
            }
        }

        return $summary;
    }

    private function getOverallSummaryData($selectedYear)
    {
        $months = Month::all();
        $yearRecord = Year::where('year', $selectedYear)->first();

        // Initialize summary array for overall data
        $summary = [
            'savings' => [
                'months' => [],
                'total' => 0
            ],
            'loans' => [
                'months' => [],
                'total' => 0
            ],
            'shares' => [
                'months' => [],
                'total' => 0
            ],
            'commodities' => [
                'months' => [],
                'total' => 0
            ],
            'members' => [
                'total' => User::where('is_admin', 0)->count(),
                'active' => 0
            ]
        ];

        // Initialize months for all categories
        foreach ($months as $month) {
            $summary['savings']['months'][$month->id] = 0;
            $summary['loans']['months'][$month->id] = 0;
            $summary['shares']['months'][$month->id] = 0;
            $summary['commodities']['months'][$month->id] = 0;
        }

        if (!$yearRecord) {
            return $summary;
        }

        // Get savings data
        $savings = Saving::where('year_id', $yearRecord->id)
            ->where('status', 'completed')
            ->get();

        foreach ($savings as $saving) {
            $summary['savings']['months'][$saving->month_id] += $saving->amount;
            $summary['savings']['total'] += $saving->amount;
        }

        // Get active savers count
        $summary['members']['active'] = Saving::where('year_id', $yearRecord->id)
            ->where('status', 'completed')
            ->distinct('user_id')
            ->count('user_id');

        // Get loan repayments data
        $repayments = LoanRepayment::where('year_id', $yearRecord->id)
            ->get();

        foreach ($repayments as $repayment) {
            $summary['loans']['months'][$repayment->month_id] += $repayment->amount;
            $summary['loans']['total'] += $repayment->amount;
        }

        // Get shares data
        $shares = Share::where('year_id', $yearRecord->id)
            ->where('status', 'completed')
            ->get();

        foreach ($shares as $share) {
            $summary['shares']['months'][$share->month_id] += $share->amount;
            $summary['shares']['total'] += $share->amount;
        }

        // Get commodity payments data
        $payments = CommodityPayment::where('status', 'approved')
            ->get();

        foreach ($payments as $payment) {
            $summary['commodities']['months'][$payment->month_id] += $payment->amount;
            $summary['commodities']['total'] += $payment->amount;
        }

        return $summary;
    }


    public function member(Request $request, User $member)
{
    $selectedYear = $request->input('year', date('Y'));

    $years = Year::orderBy('year', 'desc')->get();
    $months = Month::all();
    $members = User::where('is_admin', 0)->orderBy('surname')->get();

    $summary = $this->getMemberSummaryData($member, $selectedYear);

    return view('admin.financial-summary.member', compact(
        'summary', 'months', 'years', 'selectedYear', 'members', 'member'
    ));
}


}
// End of file

