<?php
declare(strict_types=1);

namespace App\Services\Cart;

use Illuminate\Http\Request;
use App\Services\Item\ListItemsService;

class ListCartsService
{
    private $listItemsService;

    public function __construct(
        ListItemsService $listItemsService
    )
    {
        $this->listItemsService = $listItemsService;
    }

    public function getCartItems(array $cart)
    {
        $cart_items = [];
        if (empty($cart)) {
            return $cart_items;
        }
        
        foreach($cart as $item_id => $amount){
            $cart_items[$item_id]['item'] = $this->listItemsService->find($item_id);
            $cart_items[$item_id]['amount'] = $amount;
        }

        return $cart_items;
    }

}
