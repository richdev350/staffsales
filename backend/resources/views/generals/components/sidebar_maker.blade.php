<div class="left_box">
    <div class="title_bg_gray cf"><div class="title">メーカー<span>で絞り込み</span></div><div class="kaijo"><a href="javascript:void(0)" id="clear_makers">解除</a></div></div>
    <ul class="siborikomi search-makers" >
        @foreach($makers as $maker)
            @if($loop->index <= 4)
            <li><label><input type="checkbox" name="maker_ids[]" value="{{$maker->id}}" @if(isset($conditions['maker_ids']) && in_array($maker->id, (array)$conditions['maker_ids'])) checked @endif>{{$maker->name}}</label></li>
            @endif
        @endforeach
    </ul>
    <div class="arrow_btn"><a href="#inline-content_maker" class="inline">メーカー一覧</a></div>
</div><!--left_box-->
@include('generals.components.box_maker')
