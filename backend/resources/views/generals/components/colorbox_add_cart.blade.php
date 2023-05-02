<div style="display: none;">
    <section id="inline-cart">
        <h2 class="cart-alert-title">買い物かごに追加しました</h2>
        <div class="btn_area">
            <ul>
                <li>
                    <a class="back back__add_cart">
                        <img class="hover_change_image" src="/img/button/btn_back.jpg" alt="戻る" name="back1">
                    </a>
                </li>
                <li>
                    <a href="{{route('cart.list')}}" class="next next__add_cart cart">
                        <input type="image" class="hover_change_image" src="/img/button/btn_buystep.jpg" alt="購入手続きへ" name="confirm">
                    </a>
                </li>
            </ul>
        </div>
    </section>
</div>
<a style="display: none;" id="js-colorbox" href="#inline-cart"></a>
