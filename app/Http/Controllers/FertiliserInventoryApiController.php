<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dtos\TransactionDto;
use App\Http\Requests\GetFertiliserInventoryApiRequest;
use App\Http\Requests\SaveTransactionRequest;
use App\Services\FertiliserInventoryServiceInterface;
use Carbon\Carbon;

class FertiliserInventoryApiController extends Controller
{

    private $fertiliserInventoryService;

    public function __construct(
        FertiliserInventoryServiceInterface $fertiliserInventoryService
    )
    {
        $this->fertiliserInventoryService = $fertiliserInventoryService;
    }

    /**
     * @param GetFertiliserInventoryApiRequest $request
     * @return float
     */
    public function getQuantityValue(GetFertiliserInventoryApiRequest $request): float
    {
//        Type-hinting that quantity_needed is an integer in case someone decides to refactor later down the line
//        and screw up my eons of work
//
//        Just passes the quantity_needed variable from the request to the getApplicationValue function in the fertiliserInventoryService
//        Service layer is there so that the logic isn't in the controller. That'd be ugly :(
//        I know these comments aren't needed but the exercise says "Clear comments" and this is also kinda fun.
//        Promise I won't do this in the actual app LOL
        return $this->fertiliserInventoryService->getApplicationValue((int) $request->get('quantity_needed'));
    }

    /**
     * @param SaveTransactionRequest $request
     * @return void
     */
    public function saveTransaction(SaveTransactionRequest $request)
    {
        $this->fertiliserInventoryService->saveDto(new TransactionDto(
            Carbon::now(),
            $request->get('type'),
            $request->get('quantity'),
            $request->get('unit_price'),
        ));
    }

}
