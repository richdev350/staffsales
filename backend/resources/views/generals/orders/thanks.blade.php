<?php use App\Enums\Mode\Modes; ?>
@extends('generals.layouts.app')
@if($current_mode == Modes::MAINTENANCE && AllowIpsOnMaintenance())
@include('generals.components.maintenance_mode')
@endif
@section('title', 'ご予約を受け付けました')
@section('content')
@php
    $bar_code = generateBarCode($order->id, $order->secure_code);
@endphp
    <div id="top" class="order-thanks">
        <div class="top_content cake_list active">
            <h2 class="title_border_bottom"><span class="label">注文を受け付けました</span></h2>
            <h3 class="thanks_title">お支払のご案内</h3>
            <div class="thanks_wrap clearfix">
                <div class="left">
                    <p class="red" barcode="{{ $bar_code }}">下記バーコードをレジに提示し、代金をお支払いください。</p>
                    <p class="img"><img id="barcode" order_id="{{$order->id}}" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($bar_code, 'C128', 2, 100) }}" /></p>
                    @include('generals.components.buttons.save_barcode')
                </div>
                <div class="right">
                    <dl class="clearfix">
                        <dt>ご注文番号</dt>
                        <dd class="red">
                            {{ $order->id }}
                        </dd>
                    </dl>
                </div>
            </div>
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
                @php $amount_sum = 0; @endphp
                @foreach ($order->order_details as $order_detail)
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
                    @php $amount_sum += $order_detail->amount; @endphp
                @endforeach
                <tr>
                    <th colspan="4" class="alignR">合計 {{ $amount_sum }}&nbsp;点</th>
                    <td class="alignR">{{ number_format($order->sum) }}&nbsp;円</td>
                </tr>
            </table>
            <div class="btn_wrap clearfix text-center mt-2">
                @include('generals.components.buttons.back_list')
            </div>
        </div>
    </div>
    <script>
        $(function(){
            window.onbeforeunload = function(e) {
                e.returnValue = "ページを離れようとしています。よろしいですか？";
            }
            $(".save-barcode").click(function(){
                var imgSrc = $("#barcode").attr("src");
                var orderId = $("#barcode").attr("order_id");
                var a = document.createElement("a");
                a.href = imgSrc;
                a.download = "Order_" + orderId + ".png";
                a.click();

            });
        });
    </script>
@endsection
