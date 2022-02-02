<?php
declare(strict_types=1);
namespace App\Dtos;

use Carbon\Carbon;

class TransactionDto
{
    private $date;

    private $type;

    private $quantity;

    private $unitPrice;

    public function __construct(
        Carbon $date,
        string $type,
        int $quantity,
        ?float $unitPrice
    )
    {
        $this->date = $date;
        $this->type = $type;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
    }

    /**
     * Added getters and setters here because that's what we were taught to do at university. Didn't do it anywhere else
     * "If you don't encapsulate, you will burn in hell"
     * - Sun Tzu
     */

    /**
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return float|null
     */
    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    /**
     * @param Carbon $date
     */
    public function setDate(Carbon $date): void
    {
        $this->date = $date;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @param float|null $unitPrice
     */
    public function setUnitPrice(?float $unitPrice): void
    {
        $this->unitPrice = $unitPrice;
    }
}
