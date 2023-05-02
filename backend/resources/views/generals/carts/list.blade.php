<?php use App\Enums\Mode\Modes; ?>
@extends('generals.layouts.app')
@if($current_mode == Modes::MAINTENANCE && AllowIpsOnMaintenance())
@include('generals.components.maintenance_mode')
@endif
@section('title', '現在の買い物かごの中')
@section('content')
@if($current_mode != Modes::MAINTENANCE || AllowIpsOnMaintenance())
    @include('generals.components.loading')
    <div id="top" class="cart-list">
        <div class="top_content cake_list active" id="undercolumn_cart">
            <h2 class="title_border_bottom">現在の買い物かごの中</h2>
            <div class="cart_wrap">
                @if(!empty($cart_items))
                    <p class="summary-info"></p>
                    <table summary="商品情報">
                        <col width="10%" />
                        <col width="15%" />
                        <col width="30%" />
                        <col width="15%" />
                        <col width="15%" />
                        <col width="15%" />
                        <tr>
                            <th class="alignC">削除</th>
                            <th class="alignC">商品写真</th>
                            <th class="alignC">商品名</th>
                            <th class="alignC">単価</th>
                            <th class="alignC">数量</th>
                            <th class="alignC">小計</th>
                        </tr>
                        @foreach ($cart_items as $cart_item)
                            <tr class="item"
                                item_id="{{ $cart_item['item']->id }}"
                                max_amount="{{$cart_item['item']->max_amount}}"
                                price="{{ $cart_item['item']->price }}"
                                required_delivery_fee="{{ $cart_item['item']->required_delivery_fee }}"
                                combinable_no ="{{ $cart_item['item']->combinable_no }}"
                            >
                                <td class="alignC"><a href="javascript:void(0)" class="js-delete-cart" data-name="{{ $cart_item['item']->name }}">削除</a>
                                </td>
                                <td class="alignC">
                                    @if (!($cart_item['item']->files->isEmpty()))
                                        <img src="{{ $cart_item['item']->files[0]->url }}" style="max-width: 65px;max-height: 65px;" alt="{{ $cart_item['item']->name }}" />
                                    @else
                                        <img src="/img/no-image.png" style="max-width: 65px;max-height: 65px;" alt="{{ $cart_item['item']->name }}"/>
                                    @endif
                                </td>
                                <td><strong>{{ $cart_item['item']->name }}</strong>
                                </td>
                                <td class="alignR">
                                    {{number_format($cart_item['item']->price)}}円
                                </td>
                                <td class="alignC text-center"><span class="item-amount">{{$cart_item['amount']}}</span>
                                    <ul id="quantity_level">
                                        <li @if ($cart_item['amount']==$cart_item['item']->max_amount) style="display: none;" @endif><a href="javascript:void(0)" class="js-increase"><img src="/img/button/btn_plus.jpg" width="16" height="16" alt="＋"></a></li>
                                        <li @if ($cart_item['amount']==1) style="display: none;" @endif><a href="javascript:void(0)" class="js-decrease"><img src="/img/button/btn_minus.jpg" width="16" height="16" alt="-"></a></li>
                                    </ul>
                                </td>
                                <td class="alignR subtotal">{{number_format(intval($cart_item['item']->price) * intVal($cart_item['amount']))}}円</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="5" class="alignR">合計</th>
                            <td class="alignR"><span class="price sum"></span></td>
                        </tr>
                    </table>
                    @if($barcode_status)
                        <div class="btn_area text-center mt-2">
                            @include('generals.components.buttons.continue')
                            @include('generals.components.buttons.form')
                        </div>
                    @else
                        <div class="text-center">
                            <strong class="d-block">バーコードが保存できません。<br>支払いが完了しているバーコードは削除してご利用ください。</strong>
                            <a href="{{ route('home.index') }}">
                                トップページへ
                            </a>
                        </div>
                    @endif
                @else
                    <p class="empty"><span class="font_pink">※ 現在買い物かご内に商品はございません。</span></p>
                @endif
            </div>

        </div>
        <div class="modal" tabindex="-1" role="dialog" id="confirm_delete_dialog" style="z-index: 9999">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">削除確認</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>一度削除したデータは、元に戻せません。削除しても宜しいですか？</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info yes" data-dismiss="modal">ＯＫ</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                let pre_amount;

                calculateTotal();
                resetCart();

                $(document).on("click", ".js-decrease", function () {
                    changeAmount(this, false);
                });

                $(document).on("click", ".js-increase", function () {
                    changeAmount(this, true);
                });

                function changeAmount(button, isAdd) {
                    const item = $(button).parents('.item');
                    let item_id = item.attr("item_id");
                    let maxAmount = item.attr("max_amount");
                    let amount = item.find('.item-amount').text();
                    let price = parseInt(item.attr("price"));
                    let $subtotal = item.find('.subtotal');

                    let newAmount = amount;
                    if (isAdd) {
                        newAmount++;
                        if (maxAmount < newAmount) {
                            return;
                        } else if (maxAmount == newAmount) {
                            item.find(".js-increase").parent().hide();
                        }

                        item.find(".js-decrease").parent().show();
                    } else {
                        newAmount--;
                        if (newAmount == 1) {
                            item.find(".js-decrease").parent().hide();
                        } else if (newAmount < 1) {
                            return;
                        }

                        item.find(".js-increase").parent().show();
                    }

                    let request = {
                        'item_id':item_id,
                        'amount':newAmount,
                    };

                    $.ajax({
                        url: '{{ route('cart.change') }}',
                        type: 'POST',
                        datatype: 'json',
                        data: request,
                        beforeSend: function(){
                            dispLoading();
                        }
                    })
                    .then(
                        function(response){
                            if(response.errors){
                                let errorText =  '';
                                if(response.errors.item_id){
                                    Object.keys(response.errors.item_id).forEach(function (key) {
                                        errorText += response.errors.item_id[key] + '\n';
                                    });
                                }
                                if(response.errors.amount){
                                    Object.keys(response.errors.amount).forEach(function (key) {
                                        errorText += response.errors.amount[key] + '\n';
                                    });
                                }
                                if(response.errors.can_change_amount){
                                    Object.keys(response.errors.can_change_amount).forEach(function (key) {
                                        errorText += response.errors.can_change_amount[key] + '\n';
                                    });
                                }
                                if(response.errors.exsits_stock){
                                    Object.keys(response.errors.exsits_stock).forEach(function (key) {
                                        errorText += response.errors.exsits_stock[key] + '\n';
                                    });
                                }
                                if('' !== errorText){
                                    alert(errorText);
                                    removeLoading();
                                    return;
                                }
                            }

                            item.find('.item-amount').text(newAmount);
                            calculateSubtotal($subtotal, newAmount, price);
                            calculateTotal();
                            removeLoading();
                        },
                        function(XMLHttpRequest, textStatus, errorThrown){
                            alert('変更に失敗しました。\n画面を更新して表示しなおしてください。');
                            removeLoading();
                        }
                    );
                }

                let $item;
                $('.js-delete-cart').click(function() {
                    $item = $(this).parents('.item');
                    const name = $(this).data('name');
                    $("#confirm_delete_dialog").modal("show");
                });

                $("#confirm_delete_dialog .yes").click(function(){
                    let item_id = $item.attr("item_id");
                    let request = {
                        'item_id':item_id,
                    };

                    $.ajax({
                        url: '{{ route('cart.delete') }}',
                        type: 'DELETE',
                        datatype: 'json',
                        data: request,
                        beforeSend: function(){
                            dispLoading();
                        }
                    })
                    .then(
                        function(response){
                            if(response.errors){
                                let errorText =  '';
                                if(response.errors.item_id){
                                    Object.keys(response.errors.item_id).forEach(function (key) {
                                        errorText += response.errors.item_id[key] + '\n';
                                    });
                                }
                                if('' !== errorText){
                                    alert(errorText);
                                    parent.$.fn.colorbox.close(); return false;
                                    return;
                                }
                            }

                            $item.remove();
                            calculateTotal();
                            resetCart();
                            removeLoading();
                        },
                        function(XMLHttpRequest, textStatus, errorThrown){
                            alert('変更に失敗しました。\n画面を更新して表示しなおしてください。');
                            removeLoading();
                        }
                    );
                });
            });

            function calculateSubtotal($subtotal, amount, price){
                $subtotal.text((parseInt(amount) * parseInt(price)).toLocaleString() + '円');
            }

            function calculateTotal(){

                let total = 0;

                $('.item').each(function(index, element){
                    const amount = $(element).find('.item-amount').text();
                    let subtotal = $(element).find('.subtotal').text().replace( /,/g , '');
                    subtotal = parseInt(subtotal);
                    total += subtotal;
                });

                $('.sum').text(total.toLocaleString() + '円');

            }
            function resetCart(){
                let $items = $('.item');
                if(0===$items.length){
                    $('.cart_wrap').empty().append('<p class="empty"><span class="font_pink">※ 現在買い物かご内に商品はございません。</span></p>');
                }
            }
        </script>
    </div>
@endif
@endsection