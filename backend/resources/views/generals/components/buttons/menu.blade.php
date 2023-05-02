<li class="sp">
    <a class="nav_button">
        <img src="/img/common/icon_menu.png" alt="MENU">
    </a>
    <nav class="nav_wrap">
        <div class="inner">
            <div class="nav_button_close"><a href="javascript:void(0)"><img src="/img/common/icon_menu_close.png"></a></div>
            <div class="child">
                <div class="logo">
                    <a href="{{ route('home.index') }}">
                        <img src="/img/common/top_logo.png" alt="ディスカウントドラッグコスモス 春日宝町店 オンラインショップ">
                    </a>
                </div>

                <div class="search cf">
                    <form action="{{ route('item.select') }}" method="post" id="form2">
                        {{ csrf_field() }}
                        <input type="hidden" name="sort" value="{{ isset($conditions['sort'])?$conditions['sort']:'' }}"/>
                        <input type="hidden" name="count" value="{{ isset($conditions['count'])?$conditions['count']:'' }}"/>
                        <input type="hidden" name="maker_id" value="{{ isset($conditions['maker_id'])?$conditions['maker_id']:'' }}"/>
                        <input type="hidden" name="item_category_id" value="{{ isset($conditions['item_category_id'])?$conditions['item_category_id']:'' }}"/>
                        <input type="hidden" class="form-control" name="mode" value="search" />
                        <input class="item-search" type="text" name="name" value="{{ isset($conditions['name'])?$conditions['name']:'' }}" placeholder="商品名、症状、キーワードを入力してください。"/>
                        <div class="search_icon"><input type="submit" value="検索する" /></div>
                    </form>
                </div>

                <ul class="menu01">
                    <li><a href="#inline-content_category" class="inline">カテゴリーから探す</a></li>
                    {{-- <li><a href="{{ route('item.all') }}">メーカーから探す</a></li> --}}
                    <li><a href="{{ route('cart.list') }}">カゴを見る</a></li>
                </ul>
            </div>
            <div class="box support_wrap">
            </div>
            </div><!--child-->
        </div><!--inner-->
    </nav>
</li>

@include('generals.components.box_item_category')
