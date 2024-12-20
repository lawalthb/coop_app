<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Transaction::query()->with('user');

        if (isset($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }

        if (isset($this->filters['start_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['start_date']);
        }

        if (isset($this->filters['end_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['end_date']);
        }

        if (isset($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Reference',
            'Member',
            'Description',
            'Credit Amount',
            'Debit Amount',
            'Status'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->created_at->format('Y-m-d H:i:s'),
            $transaction->reference,
            $transaction->user->surname . ' ' . $transaction->user->firstname,
            $transaction->description,
            $transaction->credit_amount ? number_format($transaction->credit_amount, 2) : '',
            $transaction->debit_amount ? number_format($transaction->debit_amount, 2) : '',
            ucfirst($transaction->status)
        ];
    }
}
