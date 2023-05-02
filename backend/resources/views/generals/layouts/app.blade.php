<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ str_replace('_', '-', app()->getLocale()) }}" xml:lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('generals.components.head')
</head>

<body id="toppage"><!--　ログインしていない時の処理-->
<div id="sb-site" class="top">
<div id="wrap">
    @include('generals.components.navbar_header')
    <script>
        $(document).ready(function(){
            $(".inline").colorbox({inline:true, maxWidth:"100%", maxHeight:"90%"});
        });
    </script>
    <!-- ホバーでメニュー表示 -->
    <script>
    $(function() {
    $("ul.dropnav > li").hover(function() {
        $("ul:not(:animated)", this).slideDown();
    }, function(){
        $("ul.child-menu", this).slideUp();
    });
    });
    </script>

    <script type="text/javascript">
    // ページの読み込みが完了してから実行
    $(function() {
        // スクロール途中から表示したいメニューバーを指定
        var navBox = $("#headbar");

        // メニューバーは初期状態では消しておく
        navBox.hide();

        // 表示を開始するスクロール量を設定(px)
        var TargetPos = 780;

        // スクロールされた際に実行
        $(window).scroll( function() {
            // 現在のスクロール位置を取得
            var ScrollPos = $(window).scrollTop();
            // 現在のスクロール位置と、目的のスクロール位置を比較
            if( ScrollPos > TargetPos ) {
                // 表示(フェイドイン)
                navBox.fadeIn();
            }
            else {
                // 非表示(フェイドアウト)
                navBox.fadeOut();
            }
        });
    });
    </script>

    <!-- 右上リスト全画面メニュー -->
    <script>
    $(function() {
    $(".nav_button").on("click", function() {
        if ($(this).hasClass("active")) {
        $(this).removeClass("active");
        $(".nav_wrap")
            .addClass("close")
            .removeClass("open");
        } else {
        $(this).addClass("active");
        $(".nav_wrap")
            .addClass("open")
            .removeClass("close");
        }
    });
    });

    $(function() {
    $(".nav_button_close").on("click", function() {
        if ($(".nav_button").hasClass("active")) {
        $(".nav_wrap")
            .addClass("close")
            .removeClass("open");
        $(".nav_button")
            .removeClass("active");
        }
    });
    });
    </script>
    
    @if (str_contains(\Route::currentRouteName(), "home."))
        <!-- InstanceBeginEditable name="EditRegion3" -->
        <div id="mainvisual" class="cf">
        </div>
        <!--mainvisual-->
        <div class="top_content">
            <div style="display: none;">@include('generals.components.sidebar')</div>
            @yield('content')
        </div>
    @else
        <div class="page_content mt-3">
            <section>
            @if (\Route::currentRouteName() != 'item.detail')
                <div class="colmun2_wrap cf">
                    <div class="left_wrap">
                        @include('generals.components.sidebar')
                    </div><!-- left_wrap -->
                    <div class="right_wrap">
                        @yield('content')
                    </div><!-- right_wrap -->
                </div><!-- 2colmun_wrap -->
            @else
                <div style="display: none;">@include('generals.components.sidebar')</div>
                @yield('content')
            </section>
            @endif
        </div>
    @endif
    @include('generals.components.footer')
    @include('generals.components.cart_modal')
</div><!-- wrap -->
</div><!-- sb-site -->
<div class="pagetop"><a href="#top">▲</a></div>
</body>

</html>
