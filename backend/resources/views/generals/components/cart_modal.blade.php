<div class="modal" tabindex="-1" role="dialog" id="cart_modal_dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">商品を買い物かごに入れました</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-100 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">お買い物を続ける</button>
                    <a href="{{ route('cart.list') }}" class="btn go-cart">買い物かごへ</a>
                </div>
                <div class="question" id="cart-question">
                    こちらもご一緒にいかがですか？
                </div>
                <div class="row" id="recommend-row">

                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="age_agree_modal_dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center age-agree-content">
                </div>
                <h1 class="text-center age-limit-title">以下の注意事項をよく読み、内容に同意した上でご購入手続きをお進みてください。</h1>
                <hr/>
                <ul class="age-limit-message">
                    <li>本品は、<span class="font_pink font-weight-bold"><span class="limit-age">20</span>歳未満</span>の方はご購入いただけません。</li>
                    <li>ご使用の際に医薬品説明文書に記載の「用法・用量」を守り、「使用上の注意（禁忌）」を必ずご確認ください。</li>
                    <li>この医薬品に関する件、その他不明点がございましたら、下記より当店の薬剤師または登録販売者にご相談ください。</li>
                </ul>
                <div class="text-center age-limit-question">
                    あなたは<span class="font_pink font-weight-bold"><span class="limit-age">20</span>歳以上</span>ですか？
                </div>
                <div class="text-center age-limit-button-bar">
                    <button class="btn btn-agree">はい</button>
                    <button class="btn btn-discard" data-dismiss="modal">いいえ</button>
                </div>
                <div class="contact_area">
                    <p>お問合せ先：ドラッグコスモス春日宝町店（TEL　092-588-9355）</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function() {
    $('.go-cart').click(function(){
        $("#cart_modal_dialog").modal('hide');
    });
    $('.btn-agree').click(function(){
        $("#age_agree_modal_dialog").modal('hide');
    });
});
</script>
