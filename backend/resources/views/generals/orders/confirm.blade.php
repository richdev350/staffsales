<?php use App\Enums\Mode\Modes; ?>
@extends('generals.layouts.app')
@if($current_mode == Modes::MAINTENANCE && AllowIpsOnMaintenance())
@include('generals.components.maintenance_mode')
@endif
@section('content')
    <div id="top" class="cart-list">
        <div class="top_content cake_list active">
            <h2 class="title_border_bottom"><span class="label">ご注文内容の確認</span></h2>
            <h3 class="subtitle_under"><span>カートの内容の確認</span></h3>
            <table summary="商品情報">
                <col width="15%" />
                <col />
                <col width="15%" />
                <col width="15%" />
                <col width="15%" />
                <tr>
                    <th class="alignC">商品写真</th>
                    <th class="alignC">商品名</th>
                    <th class="alignC">単価</th>
                    <th class="alignC">数量</th>
                    <th class="alignC">小計</th>
                </tr>
                @foreach ($order_details as $order_detail)
                    <tr class="item">
                        <td class="alignC">
                            @if (!($order_detail['item']->files->isEmpty()))
                                <img src="{{ $order_detail['item']->files[0]->url }}" style="max-width: 65px;max-height: 65px;" alt="{{ $order_detail['item']->name }}" />
                            @else
                                <img src="/img/no-image.png" style="max-width: 65px;max-height: 65px;" alt="{{ $order_detail['item']->name }}"/>
                            @endif
                        </td>
                        <td><strong>{{ $order_detail['item']->name }}</strong>
                        </td>
                        <td class="alignR">
                            {{number_format($order_detail['item']->price)}}円
                        </td>
                        <td class="alignC"><span class="item-amount">{{$order_detail['amount']}}</span>
                        </td>
                        <td class="alignR subtotal">{{number_format(intval($order_detail['item']->price) * intVal($order_detail['amount']))}}円</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="4" class="alignR">合計</th>
                    <td class="alignR">{{ number_format($sum) }}&nbsp;円</td>
                </tr>
            </table>
            <div class="btn_area text-center mt-2">
                <a href="{{ route('cart.list') }}" class="cart-btn cart-btn-prev">◀ カートの内容を編集する</a>
            </div>

            <h3 class="subtitle_under"><span>お客様情報の確認</span></h3>
            <div class="entry_wrap">
                <form
                    action="{{ route('order.confirm') }}"
                    method="post" class="register-form" id="js-order-form">
                    {{ csrf_field() }}
                    <div class="container-fluid">
                        <div class="row row__entry justify-content-center">
                            <div class="col-2">
                                <dt>お名前</dt>
                            </div>
                            <div class="col-8">
                                <dd>
                                    {{ old('name') }}
                                    <input name="name" type="hidden" value="{{ old('name') }}">
                                </dd>
                            </div>
                        </div>
                        <div class="row row__entry justify-content-center">
                            <div class="col-2">
                                <dt>社員番号</dt>
                            </div>
                            <div class="col-8">
                                <dd>
                                    {{ old('staff_id') }}
                                    <input name="staff_id" type="hidden" value="{{ old('staff_id') }}">
                                </dd>
                            </div>
                        </div>
                        <div class="row row__btn justify-content-center">
                            <div class="col text-center">
                                @include('generals.components.buttons.back')
                                @include('generals.components.buttons.regist')
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                var is_submit = false;
                $('#js-order-form').on('submit', function(){
                    if(is_submit){
                        return false;
                    }
                    is_submit = true;
                });
            </script>
        </div>
    </div>
@endsection
