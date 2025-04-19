<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CommodityPayment;
use App\Models\CommoditySubscription;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\Month;
use App\Models\Saving;
use App\Models\SavingType;
use App\Models\Share;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class MemberFinancialSummaryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedYear = $request->input('year', date('Y'));

        // Get all years for the dropdown
        $years = Year::orderBy('year', 'desc')->get();

        // Get the year_id for the selected year
        $yearRecord = Year::where('year', $selectedYear)->first();

        if (!$yearRecord) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Selected year not found in the system.');
        }

        // Get all months
        $months = Month::orderBy('id')->get();

        // Initialize the summary structure
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

        $savings = Saving::where('user_id', $user->id)
            ->where('year_id', $yearRecord->id)
            ->get();

        foreach ($savings as $saving) {
            if (isset($summary['savings'][$saving->saving_type_id])) {
                $summary['savings'][$saving->saving_type_id]['months'][$saving->month_id] += $saving->amount;
                $summary['savings'][$saving->saving_type_id]['total'] += $saving->amount;
            }
        }

        // Get loan repayments summary
        $loans = Loan::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();

        foreach ($loans as $loan) {
            $summary['loans'][$loan->id] = [
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
        $shares = Share::where('user_id', $user->id)
            ->where('year_id', $yearRecord->id)
            ->where('status', 'approved')
            ->get();

        foreach ($shares as $share) {
            $summary['shares']['months'][$share->month_id] += $share->amount;
            $summary['shares']['total'] += $share->amount;
        }

        // Get commodity payments summary
        $subscriptions = CommoditySubscription::where('user_id', $user->id)
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
            ->where('year_id', $yearRecord->id)
            ->where('status', 'approved')
            ->get();

        foreach ($payments as $payment) {
            if (isset($summary['commodities'][$payment->commodity_subscription_id])) {
                $summary['commodities'][$payment->commodity_subscription_id]['months'][$payment->month_id] += $payment->amount;
                $summary['commodities'][$payment->commodity_subscription_id]['total'] += $payment->amount;
            }
        }

           $hasData = (
        collect($summary['savings'])->sum('total') > 0 ||
        collect($summary['loans'])->sum('total') > 0 ||
        ($summary['shares']['total'] ?? 0) > 0 ||
        collect($summary['commodities'])->sum('total') > 0
    );

        return view('member.financial-summary.index', compact('summary', 'months', 'years', 'selectedYear', 'hasData'));
    }

    public function export(Request $request)
    {
        $selectedYear = $request->input('year', date('Y'));
        $user = auth()->user();

        // Get the data (similar to index method)
        $summary = $this->getSummaryData($user, $selectedYear);
        $months = Month::all();

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Financial Summary for ' . $selectedYear);
        $sheet->setCellValue('A2', 'Member: ' . $user->surname . ' ' . $user->firstname);
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
        $filename = 'financial_summary_' . $selectedYear . '_' . $user->member_no . '.xlsx';

        // Save to temp file and return for download
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function downloadPdf(Request $request)
    {
        $selectedYear = $request->input('year', date('Y'));
        $user = auth()->user();

        // Get the data (similar to index method)
        $summary = $this->getSummaryData($user, $selectedYear);
        $months = Month::all();
        $years = Year::orderBy('year', 'desc')->get();

        $pdf = PDF::loadView('member.financial-summary.pdf', compact(
            'summary', 'months', 'years', 'selectedYear', 'user'
        ));

        return $pdf->download('financial_summary_' . $selectedYear . '_' . $user->member_no . '.pdf');
    }

    // Helper method to get summary data (to avoid code duplication)
    private function getSummaryData($user, $selectedYear)
    {
        // This should contain the same logic as in your index method
        // to generate the summary data

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

        $savings = Saving::where('user_id', $user->id)
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
        $loans = Loan::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();

        foreach ($loans as $loan) {
            $summary['loans'][$loan->id] = [
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
        $shares = Share::where('user_id', $user->id)
            ->where('year_id', $yearRecord->id)
            ->where('status', 'completed')
            ->get();

        foreach ($shares as $share) {
            $summary['shares']['months'][$share->month_id] += $share->amount;
            $summary['shares']['total'] += $share->amount;
        }

        // Get commodity payments summary
        $subscriptions = CommoditySubscription::where('user_id', $user->id)
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
            ->where('year_id', $yearRecord->id)
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
}
