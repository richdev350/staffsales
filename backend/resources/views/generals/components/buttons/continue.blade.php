@php
if(strpos(parse_url(url()->previous(), PHP_URL_PATH), '/cart/agree') === 0){
    $prev_page_count = -2;
}else{
    $prev_page_count = -1;
}
@endphp
<button class="continue_button cart-btn cart-btn-prev" onclick="location.href='javascript:history.go({{ $prev_page_count }});'"><span>◀ お買い物を続ける</span></button>