<?php

namespace App\Imports;

use App\Models\Saving;
use App\Models\User;
use App\Models\SavingType;
use App\Helpers\TransactionHelper;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;

class SavingsImport implements ToModel, WithValidation, SkipsOnFailure, WithHeadingRow
{
    public function model(array $row)
    {
        $user = User::where('email', $row['member_email'])->first();
        $savingType = SavingType::find($row['saving_type_id']);

        // Calculate interest if applicable
        $amount = $row['amount'];
        $interestAmount = ($amount * $savingType->interest_rate) / 100;
        $totalAmount = $amount + $interestAmount;

        $saving = new Saving([
            'user_id' => $user->id,
            'saving_type_id' => $savingType->id,
            'amount' => $totalAmount,
            'month_id' => $row['month_id'],
            'year_id' => $row['year_id'],
            'reference' => 'SAV-' . date('Y') . '-' . Str::random(8),
            'posted_by' => auth()->id()
        ]);

        TransactionHelper::recordTransaction(
            $user->id,
            'savings',
            0,
            $totalAmount,
            'completed',
            'Monthly Savings Contribution (Interest: ' . $savingType->interest_rate . '%)'
        );

        return $saving;
    }

    public function rules(): array
    {
        return [
            'member_email' => ['required',  'exists:users,email'],
            'saving_type_id' => ['required', 'exists:saving_types,id'],
            'amount' => ['required', 'numeric'],
            'month_id' => ['required', 'exists:months,id'],
            'year_id' => ['required', 'exists:years,id'],
        ];
    }

   public function onFailure(Failure ...$failures)
{
    foreach ($failures as $failure) {
        $error = [
            'row' => $failure->row(),
            'attribute' => $failure->attribute(),
            'errors' => $failure->errors(),
            'values' => $failure->values()
        ];

        // You can log these errors
        \Log::error('Savings Import Failed:', $error);
    }

    throw new \Exception('Savings import failed. Check logs for details.');
}

}
