<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;
use App\Models\LoanRepayment;

class UpdateLoanBalances extends Command
{
    protected $signature = 'loans:update-balances';
    protected $description = 'Update balance and amount_paid columns for existing loans';

    public function handle()
    {
        $this->info('Updating loan balances...');

        $loans = Loan::all();
        $bar = $this->output->createProgressBar(count($loans));

        foreach ($loans as $loan) {
            // Calculate amount paid from repayments
            $amountPaid = LoanRepayment::where('loan_id', $loan->id)->sum('amount');

            // Update the loan
            $loan->amount_paid = $amountPaid;
            $loan->balance = $loan->total_amount - $amountPaid;
            $loan->save();

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nLoan balances updated successfully!");
    }
}
