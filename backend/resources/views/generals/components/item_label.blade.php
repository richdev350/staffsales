    @if ($item->labels && in_array('NEW', $item->labels))
        <div class="new">NEW!</div>
    @endif
    @if ($item->labels && in_array('ONLY_A_FEW', $item->labels))
        <div class="new">残りわずか</div>
    @endif
    @if ($item->labels && in_array('RECOMMEND', $item->labels))
        <div class="new">おすすめ</div>
    @endif
    @if (1 == $item->member_only)
        <div class="box gentei"><img src="/img/page/common/icon_kaiingentei.png" alt="会員限定ご提供商品"></div>
    @endif
    @if ($item->labels && in_array('SOLD_OUT_SOON', $item->labels))
        <div class="new">まもなく販売終了</div>
    @endif
    @if ($item->labels && in_array('SELF_MEDICATION', $item->labels))
        <div class="box selfmedication"><img src="/img/page/common/icon_self_medication.jpg" alt="セルフメディケーション"></div>
    @endif
    @if ($item->labels && in_array('DRINK_WATER', $item->labels))
        <div class="new">飲用水</div>
    @endif
    {{--<div class="new">ポイント2倍</div>--}}
