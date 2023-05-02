<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Entities\Cart;
use App\Services\Cart\UpdateCartService;
use App\Services\Cart\DeleteCartService;

class CartController extends Controller
{
    const SESSION_KEY_SHOP_CART = 'shop_cart';

    public function change(
        UpdateCartService $updateCartService,
        Request $request
    ){
        $messages = [];
        $errors = new MessageBag;

        if ($updateCartService->passesValidation()) {
            if($updateCartService->exsitsStock()){
                $cart = [];
                if($request->session()->has(self::SESSION_KEY_SHOP_CART)){
                    $cart = $request->session()->get(self::SESSION_KEY_SHOP_CART, array());
                }
                $is_add = $request->input('is_add');

                $current_amount = 0;
                if ($is_add && isset($cart[$request->input('item_id')])) {
                    $current_amount = $cart[$request->input('item_id')];
                }
                
                $new_amount = $current_amount + $request->input('amount');

                if($updateCartService->canChangeAmount($new_amount)){
                    $cart[$request->input('item_id')] = $new_amount;
                    $request->session()->put(self::SESSION_KEY_SHOP_CART, $cart);
                    $messages[] = '数量を変更しました。';
                }else{
                    $errors->add('can_change_amount', '数量が規定値外のため変更できませんでした。');
                }
            }else{
                $errors->add('exsits_stock', '在庫がありません。');
            }
        } else {
            $errors = $updateCartService->getValidationMessages();
        }

        return response()->json([
            'messages' => $messages,
            'errors' => $errors,
        ]);
    }

    public function add(
        UpdateCartService $updateCartService,
        Request $request
    ){
        $messages = [];
        $errors = new MessageBag;

        $cart = [];
        if($request->session()->has(self::SESSION_KEY_SHOP_CART)){
            $cart = $request->session()->get(self::SESSION_KEY_SHOP_CART, array());
        }
        $cart_amount = isset($cart[$request->input('item_id')])?$cart[$request->input('item_id')]:0;
        $current_amount = $cart_amount + $request->input('amount');

        if ($updateCartService->passesValidation()) {
            if($updateCartService->exsitsStock()){
                if($updateCartService->canChangeAmount($current_amount)){
                    $cart[$request->input('item_id')] = $current_amount;
                    $request->session()->put(self::SESSION_KEY_SHOP_CART, $cart);
                    $messages[] = '数量を変更しました。';
                }else{
                    $errors->add('can_change_amount', '数量が規定値外のため変更できませんでした。');
                }
            }else{
                $errors->add('exsits_stock', '在庫がありません。');
            }
        } else {
            $errors = $updateCartService->getValidationMessages();
        }

        $max_amount = $updateCartService->getMaxChangeableAmount($current_amount);

        return response()->json([
            'max_amount' => $max_amount,
            'messages' => $messages,
            'errors' => $errors,
        ]);
    }

    public function delete(
        DeleteCartService $deleteCartService,
        Request $request
    ){
        $messages = [];
        $errors = new MessageBag;

        if ($deleteCartService->passesValidation()) {
            $cart = [];
            if($request->session()->has(self::SESSION_KEY_SHOP_CART)){
                $cart = $request->session()->get(self::SESSION_KEY_SHOP_CART, array());
            }

            unset($cart[$request->input('item_id')]);
            $request->session()->put(self::SESSION_KEY_SHOP_CART, $cart);
            $messages[] = 'カートから削除しました。';
        } else {
            $errors = $updateCartService->getValidationMessages();
        }

        return response()->json([
            'messages' => $messages,
            'errors' => $errors,
        ]);
    }

}
