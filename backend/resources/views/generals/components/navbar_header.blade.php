<div id="header">
	<!-- <div class="top_text">
		<section>
		コスモスでしか買えないおすすめの商品がココで買える！Yahoo! JAPAN IDで新規登録/ログインしてお買い物すると毎日おトク！購入金額￥2,000(税込)以上で配送料無料(一部商品を除く)
		</section>
	</div> -->
	<div class="head_wrap">
		<section>
			<div class="head_area cf">
				<h1 class="logo">
                    <a href="{{ route('home.index') }}">
                    <img src="{{asset('img/common/top_logo.png')}}" alt="ディスカウントドラッグコスモス 春日宝町店 オンラインショップ">
                    </a>
                </h1>
				<div class="search cf">
                    @include('generals.components.navbar_header_search')
				</div>

				<div class="menu_list cf">
					<ul>
                        @include('generals.components.buttons.cart')
                        @include('generals.components.buttons.menu')
					</ul>
				</div>
			</div>
		</section>
    </div><!--head_wrap-->

    @include('generals.components.category_header')

</div><!--#header-->
