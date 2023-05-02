<?php use App\Enums\Mode\Modes; ?>
@extends('generals.layouts.app')
@if($current_mode == Modes::MAINTENANCE && AllowIpsOnMaintenance())
@include('generals.components.maintenance_mode')
@endif
@section('title', $item->name.' | 商品詳細')
@section('content')
@if($current_mode != Modes::MAINTENANCE || AllowIpsOnMaintenance())
@include('generals.components.loading')
@include('generals.components.colorbox_add_cart')
@include('generals.components.add_cart_js')
<div class="page_content page_products syousai">
    <form name="form1" id="form1" method="post" action="?">
        <input type="hidden" id="item_id" value="{{ $item->id }}">
            <div class="colmun2_wrap cf">
                <div class="left_wrap">
                    <div class="item_wrap cf">
                        <div class="left_item_wrap">
                            <div class="page_item_wrap">
                                <div class="title sp">{{ $item->name }}</div>
                                <ul class="page_item_sliderArea slider">
                                    @if (!($item->files->isEmpty()))
                                        @foreach ($item->files as $file)
                                            <li class="main_img"><a href="#inline-item{{$loop->count}}" class="inline" data-mh="img"><div class="inner"><img src="{{ file_url($file) }}" alt="{{ $item->name }}"></div></a></li>
                                        @endforeach
                                    @endif
                                </ul>

                                <ul class="page_item_thumbnail_wrap slider cf">
                                    @if (!($item->files->isEmpty()))
                                        @foreach ($item->files as $file)
                                            <li class="thumbnail thumbnail-current"><a data-slide-index="0" href="javascript:void(0)" data-mh="thumbnail"><img src="{{ file_url($file) }}" alt="{{ $item->name }}"></a></li>
                                        @endforeach
                                    @endif
                                </ul>

                                <!-- スライダー商品カラーボックス -->
                                @if (!($item->files->isEmpty()))
                                    @foreach ($item->files as $file)
                                        <div style="display: none;"><section id="inline-item{{$loop->count}}">
                                            <div class="content_inner_img"><img src="{{ file_url($file) }}" alt="{{ $item->name }}"></div><!-- content_inner -->
                                        </section></div>
                                    @endforeach
                                @endif
                            </div><!--sliderArea-->
                        </div><!--left_item_wrap-->

                        <div class="right_item_info">
                            <div class="tag_wrap cf">
                                @include('generals.components.item_label')
                            </div><!--tag_wrap-->
                            <div class="cord">商品コード： {{ $item->jan }} </div>
                            <h2 class="title pc">{{ $item->name }}</h2>
                            <div class="price"><span>￥{{ number_format($item->price) }}</span>（税込）</div>
                            <div class="text">
                            {!! $item->abridge !!}<br>
                            {!! $item->summary !!}<br>
                            </div>
                        </div><!--right_item_info-->
                    </div>
                    <div class="container">
                        <div class="setumei_wrap">
                            <div class="item_setumei">
                                <h2 class="title_line">
                                    @if ($item->description_title)
                                        {{ $item->description_title }}
                                    @endif
                                </h2>
                                <input id="trigger1" class="grad-trigger" type="checkbox">
                                <label class="grad-btn" for="trigger1"></label>
                                <div class="grad-item">
                                    <div class="text">
                                        {!! $item->description !!}
                                    </div>
                                </div>
                            </div><!--item_setumei-->
                        </div><!--setumei_wrap-->
                    </div><!--container-->

                    <div class="sp sp_area">
                        <div class="cart_area">
                            <div class="inner">
                                @if ($current_mode == Modes::SALES || AllowIpsOnMaintenance())
                                <div class="quantity">数量:{{ $item->max_amount }}
                                    <select name="item_quantity" class="box">
                                        @foreach (range(1,$item->max_amount) as $count)
                                            <option value="{{ $count }}" {{ $count == 1? "selected":"" }}>{{ $count }}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" name="item_quantity" class="box" value="1" maxlength="{{ $item->max_amount }}" style=""> --}}
                                </div>
                                <!--★カゴに入れる★-->
                                <div class="cartbtn" id="cartbtn_default">
                                    <a class="js-add-cart" href="javascript:void(0);">
                                        <img src="/img/page/common/btn_cartin.png" alt="カゴに入れる" name="cart" id="cart" />
                                    </a>
                                </div>
                                @endif
                                <!--個数表記を水or通常で変更：カテゴリID取得でstyle生成→Wステータスの場合は消去する仕組み-->
                                <style>
                                    span.drink_check1{display:none;}
                                </style>
                                <style>
                                    span.drink_check12{display:none;}
                                </style>
                            </div><!--inner-->
                            <div class="red_text">
                                <ul class="li_kome">
                                    <li>お買物は、１回のご注文で１商品1～{{ $item->max_amount }}個 までとさせていただきます。</li>
                                    <li>転売目的でのご注文はご遠慮いただいております。</li>
                                </ul>
                            </div>
                        </div>
                        <div class="share_wrap cf">
                            <div class="text">シェアする</div>
                            <div class="btn_area">
                                <a href="https://twitter.com/share"><img src="/img/page/common/icon_twitter.png" alt="Twitter"></a>
                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                                <a href="#"><img src="/img/page/common/icon_facebook.png" alt="Facebook"></a>
                                <div class="line-it-button" data-lang="ja" data-type="share-c" data-ver="3" data-url="{{ route('item.detail', ['item_category_id' => $item->item_categories[0]->id, 'id' => $item->id]) }}" data-color="default" data-size="large" data-count="false" style="display: none;"></div>
                                <script src="https://d.line-scdn.net/r/web/social-plugin/js/thirdparty/loader.min.js" async="async" defer="defer"></script>
                            </div>
                        </div>
                    </div>
                </div><!--left_wrap-->
                <div class="right_wrap">
                    <div class="right_fix">
                    <div class="cart_area">
                        <div class="inner">
                            @if ($current_mode == Modes::SALES || AllowIpsOnMaintenance())
                            <div class="quantity">数量：
                                <select name="item_quantity" class="box">
                                    @foreach (range(1,$item->max_amount) as $count)
                                        <option value="{{ $count }}" {{ $count == 1? "selected":"" }}>{{ $count }}</option>
                                    @endforeach
                                </select>
                                {{-- <input type="text" name="item_quantity" class="box" value="1" size="3" maxlength="3" max="{{ $item->max_amount }}" style=""> --}}
                            </div>
                            <!--★カゴに入れる★-->
                            <div class="cartbtn" id="cartbtn_default">
                                <a class="js-add-cart" href="javascript:void(0);">
                                    <img src="/img/page/common/btn_cartin.png" alt="カゴに入れる" name="cart" id="cart" />
                                </a>
                            </div>
                            @endif
                            <!--個数表記を水or通常で変更：カテゴリID取得でstyle生成→Wステータスの場合は消去する仕組み-->
                            <style>
                                span.drink_check1{display:none;}
                            </style>
                            <style>
                                span.drink_check12{display:none;}
                            </style>
                        </div><!--inner-->
                        <div class="red_text">
                            <ul class="li_kome">
                                <li>お買物は、１回のご注文で１商品1～{{ $item->max_amount }}個 までとさせていただきます。</li>
                                <li>転売目的でのご注文はご遠慮いただいております。</li>
                            </ul>
                        </div>
                    </div>
                    </div><!--right_fix-->
                </div><!--right_wrap-->
            </div><!-- 2colmun_wrap -->
        </section>
    </div>
</form>
<div>
<script>
    //商品詳細ページ画像スライダー
    $(function() {
        $('.page_item_sliderArea').slick({
        infinite: true,
        slidesToShow: 1,
        autoplay: false,
        slidesToScroll: 1,
        arrows: true,
        fade: true,
        asNavFor: '.page_item_thumbnail_wrap'
        });
        $('.page_item_thumbnail_wrap').slick({
        accessibility: true,
        autoplay: false,
        autoplaySpeed: 4000,
        speed: 400,
        arrows: false,
        infinite: true,
        slidesToShow: 20,
        slidesToScroll: 20,
        asNavFor: '.page_item_sliderArea',
        focusOnSelect: true,
        });
    });
</script>
@endif
@endsection