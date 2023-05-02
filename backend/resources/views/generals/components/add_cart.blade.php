<div class="buy">
    <div class="title">{{ $item->name }}</div>
    <div class="price">{{ number_format($item->price) }}円<span class="tax">（税込）</span></div>

    <div class="num clearfix">
        <div class="dec js-decrease"><img src="{{ config('app.root_path') }}/img/icon_-.png" alt=""></div>
        <div class="now"><input id="cart-amount" type="text" min="{{$min_amount}}" max="{{$item->max_amount - $amount}}" step="1" value="@if($item->max_amount - $amount === 0) 0 @else 1 @endif" readonly></div>
        <div class="inc js-increase"><img src="{{ config('app.root_path') }}/img/icon_+.png" alt=""></div>
        <input id="item-id" type="hidden" value="{{$item->id}}">
    </div>

    <div class="btn-wrapper">
        <div class="btn">
            @include('generals.components.buttons.add_cart')
        </div>
    </div>
</div>
