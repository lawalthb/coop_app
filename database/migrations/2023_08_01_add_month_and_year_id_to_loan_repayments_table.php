<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loan_repayments', function (Blueprint $table) {
            $table->foreignId('month_id')->nullable()->after('payment_method');
            $table->foreignId('year_id')->nullable()->after('month_id');
        });

        // Set default values for existing records based on payment_date
        $repayments = DB::table('loan_repayments')->get();

        foreach ($repayments as $repayment) {
            if ($repayment->payment_date) {
                $date = Carbon::parse($repayment->payment_date);
                $monthNumber = $date->month;
                $year = $date->year;

                // Get the month_id from the months table
                $month = DB::table('months')->where('id', $monthNumber)->first();
                $monthId = $month ? $month->id : null;

                // Get the year_id from the years table
                $yearRecord = DB::table('years')->where('year', $year)->first();
                $yearId = $yearRecord ? $yearRecord->id : null;

                // Only update if we found valid IDs
                if ($monthId && $yearId) {
                    DB::table('loan_repayments')
                        ->where('id', $repayment->id)
                        ->update([
                            'month_id' => $monthId,
                            'year_id' => $yearId
                        ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_repayments', function (Blueprint $table) {
            $table->dropColumn('month_id');
            $table->dropColumn('year_id');
        });
    }
};
