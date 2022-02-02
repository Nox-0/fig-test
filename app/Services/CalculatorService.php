<?php
declare(strict_types=1);
namespace App\Services;

/**
 * This is a new service because its functionality is for calculating and nothing to do with the fertiliserInventory
 * Also it would be used in other parts of the app if it were to be expanded
 */
class CalculatorService
{
    public const INFLATION_VALUE = 100;
    public function deflate(int $deflatable): float
    {
        return (float) ($deflatable / self::INFLATION_VALUE);
    }

    public function inflate(float $inflatable): int
    {
        return (int) (round($inflatable, 2) * self::INFLATION_VALUE);
    }
}
