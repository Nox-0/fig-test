<?php
declare(strict_types=1);
namespace App\Services;

use App\Dtos\TransactionDto;

interface FertiliserInventoryServiceInterface
{
    public function getApplicationValue(int $quantityRequested): float;

    public function saveDto(TransactionDto $transactionDto);
}
