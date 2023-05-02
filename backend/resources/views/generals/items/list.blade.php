<?php use App\Enums\Mode\Modes; ?>
@extends('generals.layouts.app')
@if($current_mode == Modes::MAINTENANCE && AllowIpsOnMaintenance())
@include('generals.components.maintenance_mode')
@endif
@section('title', ($item_category?$item_category->name.' | ':'').'商品一覧')
@section('content')
@if($current_mode != Modes::MAINTENANCE || AllowIpsOnMaintenance())
@include('generals.components.loading')
@include('generals.components.colorbox_add_cart')
@include('generals.components.add_cart_js')
<div class="page_products">
    <div class="container">
        @if ($item_category && count($paginator)>0)
        <h2 class="title_border_bottom">「{{ $item_category->name }}」の商品一覧</h2>
        @endif
        @if (count($paginator)>0)
            @include('generals.components.pagination_item_top', ['paginator' => $paginator])
        @endif
    </div>

    @if (count($paginator)>0)
    <!-- ▼商品一覧▼ -->
    <div class="products_item_wrap cf pattern1">
        @foreach ($paginator as $item)
            <div class="item_box cf" data-mh="item_box">
            <form name="" action="" onsubmit="return false;">
                <div class="img_area">
                    @include('generals.components.item_label')
                    <div class="img">
                        @include('generals.components.item_image')
                    </div>
                </div>
                <div class="text_area">
                <a href="{{ route('item.detail', ['item_category_id' => $item->item_categories[0]->id, 'id' => $item->id]) }}">
                    <div class="title">{{ $item->name }}</div>
                    <div class="info">
                        <span class="cord">{{ $item->jan }}</span>
                    </div>
                    <span class="text">{!! $item->abridge !!}</span>
                </a>
                </div>
                <div class="cart_area">
                    <span class="price">￥{{ number_format($item->price) }}<span>（税込）</span></span>
                    @if ($current_mode == Modes::SALES || AllowIpsOnMaintenance())
                    <div class="quantity">
                        <select name="quantity" class="box">
                            @foreach (range(1,$item->max_amount) as $count)
                                <option value="{{ $count }}" {{ $count == 1? "selected":"" }}>{{ $count }}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" name="quantity" class="box" value="1" size="3" maxlength="3" min="1" max="{{$item->max_amount}}" style=""> --}}
                    </div>
                    <!--★カゴに入れる★-->
                    <div class="cartbtn" id="cartbtn_default_1">
                        <input class="add-cart-button" data="{{$item->id}}" type="image" src="{{asset('img/page/common/cart_newicon.png')}}" alt="カゴに入れる">
                    </div>
                    @endif
                </div>
            </form>
            </div><!--item_box-->
        @endforeach
        @if (count($paginator)>0)
            @include('generals.components.pagination_item_bottom', ['paginator' => $paginator])
        @endif
    </div>
    @else
        <div id="undercolumn_error">
            <div class="message_area">
                <!--★エラーメッセージ-->
                <p class="error">該当件数<strong>0件</strong>です。<br>他の検索キーワードより再度検索をしてください。</p>
            </div>
        </div>
    @endif
</div>
@endif
@endsection