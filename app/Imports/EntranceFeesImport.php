<?php

namespace App\Imports;

use App\Models\EntranceFee;
use App\Models\User;
use App\Models\Month;
use App\Models\Year;
use App\Helpers\TransactionHelper;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EntranceFeesImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $user = User::where('email', $row['email'])->firstOrFail();
        $month = Month::findOrFail($row['month']);
        $year = Year::findOrFail($row['year']);

        $entranceFee = EntranceFee::create([
            'user_id' => $user->id,
            'amount' => $row['amount'],
            'month_id' => $month->id,
            'year_id' => $year->id,
            'remark' => $row['remark'] ?? null,
            'posted_by' => auth()->id()
        ]);

        TransactionHelper::recordTransaction(
            $user->id,
            'entrance_fee',
            0,
            $row['amount']
        );

        return $entranceFee;
    }

    public function rules(): array
    {
        return [
            'email' => ['required','exists:users,email'],
            'amount' => ['required', 'numeric', 'min:0'],
            'month' => ['required', 'exists:months,id'],
            'year' => ['required', 'exists:years,id'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'email.exists' => 'User with email :input not found in the system',
            'month.exists' => 'Invalid month ID',
            'year.exists' => 'Invalid year ID'
        ];
    }
}
