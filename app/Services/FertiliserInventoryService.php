<?php
declare(strict_types=1);
namespace App\Services;

use App\Dtos\TransactionDto;
use App\Models\Transaction;
use App\Repositories\TransactionsRepository;
use Exception;
use Illuminate\Support\Collection;
use Throwable;

/**
 * This is the meat of the app
 * Provides most of the logic for the api calls
 */
class FertiliserInventoryService implements FertiliserInventoryServiceInterface
{
    public const APPLICATION = 'Application';
    public const PURCHASE = 'Purchase';

    /**
     * @param int $quantityRequested
     * @return float
     * @throws Throwable
     */
    public function getApplicationValue(int $quantityRequested): float
    {
        $data = (new TransactionsRepository())->getTransactionDtos();

        // throws exception if the quantity isn't available
        throw_if(
            !$this->quantityAvailable($quantityRequested, $data),
            new Exception('Quantity requested is not available', 412)
        );

        $availableInventory = $this->getAvailableInventory($data);

        // takes the inventory left after using up the current purchases and figures out
        // which purchases are needed for the new application
        $purchasesNeeded = $this->getPurchasesNeeded($quantityRequested, $availableInventory);

        $applicationValue = $purchasesNeeded->sum(function (TransactionDto $purchase) {
            return $purchase->getQuantity() * $purchase->getUnitPrice();
        });

        // deflates the value because it's currently stored in 100s due to float addition and other operations being
        // super funky due to binary and rounding
        return (new CalculatorService)->deflate((int) $applicationValue);
    }


    /**
     * @param TransactionDto $transactionDto
     * @return void
     * @throws Throwable
     */
    public function saveDto(TransactionDto $transactionDto)
    {
        if ($transactionDto->getType() === FertiliserInventoryService::APPLICATION) {
            $data = (new TransactionsRepository())->getTransactionDtos();

            throw_if(
                !$this->quantityAvailable($transactionDto->getQuantity(), $data),
                new Exception('Quantity requested is not available', 412)
            );
        }

        // creates a transaction with null as the unit price if it's an application, instead of 0 or whatever the user passes
        $unitPrice = null;
        if ($transactionDto->getType() === 'Purchase') {
            $unitPrice = (int)round((new CalculatorService)->inflate($transactionDto->getUnitPrice()));
        }
        $transaction = new Transaction([
            'quantity' => $transactionDto->getQuantity(),
            'unit_price' => $unitPrice,
            'date' => $transactionDto->getDate(),
            'type' => $transactionDto->getType(),
        ]);


        $transaction->save();
    }


    /**
     * @param $data
     * @return Collection
     */
    private function getAvailableInventory($data): Collection
    {
        $purchases = collect();

        /**
         * @var TransactionDto $transaction
         */
        // if the transaction is a purchase its added to the purchases collection.
        // if it's an application, the purchases that are available are used until the application's quantity is depleted
        foreach ($data as $transaction) {
            if ($transaction->getType() === self::PURCHASE) {
                $purchases->add($transaction);
            }

            if ($transaction->getType() === self::APPLICATION) {
                $purchases = $this->useAvailable($transaction->getQuantity(), $purchases);
            }
        }

        return $purchases;
    }


    /**
     * @param int $quantityNeeded
     * @param Collection $transactions
     * @return bool
     */
    public function quantityAvailable(int $quantityNeeded, Collection $transactions): bool
    {
        // checks if there's enough quantity to meet quantityNeeded
        return $transactions->sum(function ($transaction) { return $transaction->getQuantity(); }) >= $quantityNeeded;
    }


    /**
     * @param int $quantityNeeded
     * @param Collection $purchases
     * @return Collection
     */
    public function useAvailable(int $quantityNeeded, Collection $purchases): Collection
    {
        // $quantityNeeded is a negative integer which is confusing to work with (for my big brain), so I make it positive
        $quantityNeeded = abs($quantityNeeded);
        $purchasesLeft = collect();
        /**
         * @var TransactionDto $purchase
         */
        foreach ($purchases as $purchase) {
            // we try to use up all of the quantityNeeded with the purchase's quantity
            if ($quantityNeeded < $purchase->getQuantity()) {
                $purchase->setQuantity($purchase->getQuantity() - $quantityNeeded);
                $purchasesLeft->add($purchase);
                $quantityNeeded = 0;
            }

            // if the purchase doesn't have enough quantity, it's not added to the purchasesLeft collection
            // due to having 0 quantity after being 'used'
            if ($quantityNeeded >= $purchase->getQuantity()) {
                $quantityNeeded -= $purchase->getQuantity();
            }
        }

        return $purchasesLeft;
    }


    /**
     * @param int $quantityNeeded
     * @param Collection $purchases
     * @return Collection
     */
    private function getPurchasesNeeded(int $quantityNeeded, Collection $purchases): Collection
    {
        $purchasesNeeded = collect();
        /**
         * @var TransactionDto $purchase
         */
        // Adds only as many purchases as needed according to quantityNeeded variable
        foreach ($purchases as $purchase) {
            if ($quantityNeeded <= $purchase->getQuantity() || $quantityNeeded <= 0) {
//                Changes the quantity of the purchase because we don't need all of it to get to quantityNeeded
                $purchase->setQuantity($quantityNeeded);
                $purchasesNeeded->add($purchase);
                break;
            }

            $quantityNeeded -= $purchase->getQuantity();
            $purchasesNeeded->add($purchase);
        }

        return $purchasesNeeded;
    }
}
