<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Dtos\TransactionDto;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TransactionsRepository
{
    /**
     * Returns a collection of ALL transactions and maps them to transaction DTOs
     * @return Collection
     */
    public function getTransactionDtos(): Collection
    {
        $data = Transaction::select(['date', 'type', 'quantity', 'unit_price'])->orderBy('date', 'ASC')->get();

        return $data->map(function ($transaction) {
            return new TransactionDto(
                new Carbon($transaction->date),
                $transaction->type,
                $transaction->quantity,
                $transaction->unit_price
            );
        });
    }
}
