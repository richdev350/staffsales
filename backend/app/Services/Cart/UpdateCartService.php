<?php
declare(strict_types=1);

namespace App\Services\Cart;

use Throwable;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Entities\Item;
use App\Services\Item\ListItemsService;
use App\Http\Requests\Cart\SaveCartRequestFilter;
use App\Http\Requests\Cart\SaveCartRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;

class UpdateCartService
{
    use Filterable,
        Validatable;

    private $request;
    private $listItemsService;

    public function __construct(
        ListItemsService $listItemsService,
        Request $request
    ){
        $this->request = $request;
        $this->listItemsService = $listItemsService;

        $this->setRequestFilter(new SaveCartRequestFilter());
        $this->setFormRequest(new SaveCartRequest());
        $this->init();
    }

    public function init()
    {
        if (! $this->request->isMethod('GET')) {
            $this->filterInputs();
            return;
        }

        $this->request->flush();
    }

    public function exsitsStock(){
        $item = $this->listItemsService->find($this->request->input('item_id'));
        if(!$item->is_stock){
            return true;
        }
        // TODO 在庫確認する場合の実装
        return false;
    }

    public function canChangeAmount(int $current_amount){
        $item = $this->listItemsService->find($this->request->input('item_id'));
        return $item->max_amount >= $current_amount && Item::MIN_AMOUNT <= $current_amount;
    }

    public function getMaxChangeableAmount(int $current_amount){
        $item = $this->listItemsService->find($this->request->input('item_id'));
        return $item->max_amount >= $current_amount ? $item->max_amount - $current_amount : 0;
    }

}
