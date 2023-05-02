<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ str_replace('_', '-', app()->getLocale()) }}" xml:lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('generals.components.head')
</head>
<body id="toppage">
    <div id="sb-site" class="top">
        <div id="wrap">
            <div id="header">
                <div class="head_wrap">
                    <section>
                        <div class="head_area cf">
                            <h1 class="logo">
                            <a href="{{ route('home.index') }}">
                            <img src="{{asset('img/common/top_logo.png')}}" alt="ディスカウントドラッグコスモス 春日宝町店 オンラインショップ">
                            </a>
                            </h1>
                        </div>
                    </section>
                </div>
            </div>
            <div style = "text-align: center; margin: 0; width: 100%; transform: translateY(100%);">
                <h1>現在、販売は行っておりません。</h1>
                <h1>次回開催をお待ちください</h1>
            </div>
            <footer id="footer">
                <div class="copy"> Copyright© 2021 COSMOS Pharmaceutical　Corporation All rights Reserved.</div>
            </footer>
        </div>
    </div>
</body>
</html>
<style>
    footer {
       position: fixed;
       bottom: 0px;
       width: 100%;
    },
    .text h1 {
       text-align: center;
       margin: 0;
       width: 100%;
       transform: translateY(100%);
    }
</style>
