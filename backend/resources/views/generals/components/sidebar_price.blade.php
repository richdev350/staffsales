<div class="left_box">
    <div class="title_bg_gray cf"><div class="title">価格帯<span>で絞り込み</span></div><div class="kaijo"><a href="javascript:void(0)" id="clear_price">解除</a></div></div>
    <ul class="siborikomi search-price">
        @foreach(price_search_list() as $key => $value)
        <li><label><input type="radio" name="price_id" value="{{$key}}" @if(isset($conditions['price_id']) && $key == $conditions['price_id']) checked @endif>{{ $value['label'] }}</label></li>
        @endforeach
    </ul>
</div><!--left_box-->