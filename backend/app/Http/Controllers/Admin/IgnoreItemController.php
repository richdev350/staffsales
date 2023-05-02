<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Models\Entities\Item;
use App\Models\Entities\Shop;
use App\Services\Item\ListItemsService;
use App\Services\Shop\UpdateShopService;
use App\Services\Maker\ListMakersService;

class IgnoreItemController extends Controller
{
    public function list(
        ListItemsService $listItemsService,
        ListMakersService $listMakersService,
        $shop_id
    ) {
        $shop = Shop::find($shop_id);
        $makers = $listMakersService->list();
        $conditions = [];
        $conditions['orders'] = ['sort_no' => 'ASC'];
        $items = $listItemsService->list($conditions);

        return response()->view('admins.ignore-items.list', compact(
            'shop',
            'makers',
            'items'
        ));
    }

    public function batch(
        UpdateShopService $updateSHopService,
        Request $request,
        $shop_id
    ) {
        try {
            switch ($request->input('action')) {
                case 'ignore':
                    $count = $updateSHopService->ignoreItems();
                    $request->session()->flash('message', sprintf('%s 件の商品を「非取扱商品」にしました。', number_format($count)));
                    break;
                default:
                    break;
            }
        } catch (Throwable $exception) {
            throw $exception;
        }

        return redirect()->route('admin.ignore-item.list', ['shop_id' => $shop_id]);
    }
}
