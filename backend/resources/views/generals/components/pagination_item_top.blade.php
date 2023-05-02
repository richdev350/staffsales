<?php /* このファイルはUTF-8のBOMなし(UTF-8N)で保存しています */ ?>
<?php
    $sort_list = [
        'sort_no' => '並び替え',
        'price_asc' => '価格順(低い順)',
        'price_desc' => '価格順(高い順)',
        'created_desc' => '新着順',
        'quantity' => '売れ筋順',
        'sales' => '売上順',
    ];
    $count_list = [15, 30, 50];
?>
<form name="page_navi_top" class="page_navi_top" action="">
    <div class="siborikomi_bar cf">
        <div class="box number" data-mh="box">
            @php
                if(1 == $paginator->currentPage()){
                    $count_start = 1;
                    $count_end = count($paginator);
                }else{
                    $count_start = ($paginator->currentPage() - 1)*intval($conditions['count']);
                    $count_end = ($paginator->currentPage() - 1)*intval($conditions['count']) + count($paginator);
                }
            @endphp
            <div class="text">対象商品<span> {{number_format($paginator->total())}} </span>件（{{$count_start}}～{{$count_end}}件目を表示）</div>
        </div>
        <div class="box narabikae" data-mh="box">
            <div class="select_wrap">
            <select class="select_sort" slize="1">
                @foreach($sort_list as $key => $value)
                    <option value="{{$key}}" {{ isset($conditions['sort'])&&$conditions['sort']==$key?"selected":"" }}>{{$value}}</option>
                @endforeach
            </select>
            </div>
        </div>
        <div class="box kensuu" data-mh="box">
            <div class="select_wrap">
            <select class="select_count" slize="1">
                @foreach($count_list as $count)
                    <option value={{$count}} {{ isset($conditions['count'])&&$conditions['count']==$count?"selected":"" }}>{{$count}}件表示</option>
                @endforeach
            </select>
            </div>
        </div>
        <div class="box hyouji" data-mh="box">
            <a class="hyouji1 active" href="javascript:void(0)"><img src="{{asset('img/page/common/icon_hyouji01.png')}}" alt="サムネイル・テキスト表示"></a>&nbsp;
            <a class="hyouji2" href="javascript:void(0)"><img src="{{asset('img/page/common/icon_hyouji02.png')}}" alt="サムネイル表示"></a>
        </div>
        <script>
            $(document).ready(function(){
                $(".page_navi_top .select_sort").change(function() {
                    var sort = $(this).val();
                    $("#search_sort").val(sort);
                    $("#search_form").submit();
                });

                $(".page_navi_top .select_count").change(function() {
                    var count = $(this).val();
                    $("#search_count").val(count);
                    $("#search_form").submit();
                });

                $('.siborikomi_bar .box.hyouji .hyouji1').on('click', function () {
                    $('.siborikomi_bar .box.hyouji .hyouji1').addClass('active');
                    $('.siborikomi_bar .box.hyouji .hyouji2').removeClass('active');

                    $('.products_item_wrap').addClass('pattern1');
                    $('.products_item_wrap').removeClass('pattern2');
                });

                $('.siborikomi_bar .box.hyouji .hyouji2').on('click', function () {
                    $('.siborikomi_bar .box.hyouji .hyouji2').addClass('active');
                    $('.siborikomi_bar .box.hyouji .hyouji1').removeClass('active');

                    $('.products_item_wrap').addClass('pattern2');
                    $('.products_item_wrap').removeClass('pattern1');
                });
            });
        </script>
    </div>
</form>
