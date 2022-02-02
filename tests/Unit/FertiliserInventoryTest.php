<?php

namespace Tests\Unit;

use App\Dtos\TransactionDto;
use App\Services\FertiliserInventoryService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class FertiliserInventoryTest extends TestCase
{
    private $FertiliserInventoryService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->FertiliserInventoryService = new FertiliserInventoryService();
    }

    /**
     * @dataProvider getTransactions
     * @return void
     */
    public function testQuantityAvailable($quantityNeeded, $data, $expected)
    {
        $this->assertEquals(
            $expected,
            $this->FertiliserInventoryService->quantityAvailable($quantityNeeded, $data)
        );
    }

    /**
     * @dataProvider getPurchases
     */
    public function testUseAvailable($quantityNeeded, $purchases, $expected)
    {
        $this->assertEquals(
            $expected,
            $this->FertiliserInventoryService->useAvailable($quantityNeeded, $purchases)
        );
    }

    public function getPurchases(): array
    {
        return [
            [
                'quantityNeeded' => 1,
                'data' => collect([
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 5, 1.5),
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 6, 2.5),
                ]),
                'expected' => collect([
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 4, 1.5),
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 6, 2.5),
                ]),
            ],
            [
                'quantityNeeded' => 2,
                'data' => collect([
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 5, 1.5),
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 6, 2.5),
                ]),
                'expected' => collect([
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 3, 1.5),
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 6, 2.5),
                ]),
            ],
            [
                'quantityNeeded' => 10,
                'data' => collect([
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 5, 1.5),
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 5, 2.5),
                ]),
                'expected' => collect(),
            ],
            [
                'quantityNeeded' => 100,
                'data' => collect([
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 55, 1.5),
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 50, 2.5),
                ]),
                'expected' => collect([
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 5, 2.5),
                ]),
            ],
        ];
    }

    public function getTransactions(): array
    {
        return [
            [
                'quantityNeeded' => 1,
                'data' => collect([
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 5, 1.5),
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 6, 2.5),
                    new TransactionDto((new Carbon('2020-11-11')), 'Application', -11, null),
                ]),
                'expected' => false,
            ],
            [
                'quantityNeeded' => 2,
                'data' => collect([
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 5, 1.5),
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 6, 2.5),
                    new TransactionDto((new Carbon('2020-11-11')), 'Application', -9, null),
                ]),
                'expected' => true,
            ],
            [
                'quantityNeeded' => 10,
                'data' => collect([
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 5, 1.5),
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 4, 2.5),
                ]),
                'expected' => false,
            ],
            [
                'quantityNeeded' => 4,
                'data' => collect([
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 55, 1.5),
                    new TransactionDto((new Carbon('2020-11-11')), 'Purchase', 50, 2.5),
                    new TransactionDto((new Carbon('2020-11-11')), 'Application', -100, null),
                ]),
                'expected' => true,
            ],
        ];
    }
}
