<?php

namespace App\Imports;

use App\Models\Share;
use App\Models\User;
use App\Models\ShareType;
use App\Helpers\TransactionHelper;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SharesImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $user = User::where('email', $row['email'])->first();
        $shareType = ShareType::first();

        $share = Share::create([
            'user_id' => $user->id,
            'share_type_id' => $shareType->id,
            'certificate_number' => 'SHR-' . date('Y') . '-' . Str::random(8),
            'amount_paid' => $row['amount'],
            'month_id' => $row['month_id'],
            'year_id' => $row['year_id'],
            'posted_by' => auth()->id(),
            'status' => 'approved'
        ]);

        TransactionHelper::recordTransaction(
            $user->id,
            'share_purchase',
            0,
            $row['amount'],
            'completed',
            'Share Purchase - ' . $share->certificate_number
        );

        return $share;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|exists:users,email',
            'amount' => 'required|numeric|min:1',
            'month_id' => 'required|exists:months,id',
            'year_id' => 'required|exists:years,id'
        ];
    }
}
