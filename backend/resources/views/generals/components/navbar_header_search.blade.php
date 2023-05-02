
<!-- ▲【新】ヘッダーゾーン --><!-- ▼商品検索 -->
<!-- スマホハンバーガーメニュー -->
<form action="{{ route('item.select') }}" method="post" id="header_search_form">
    {{ csrf_field() }}
    <input class="sbox1 item-search" name="name" value="{{ isset($conditions['name'])?$conditions['name']:'' }}" placeholder="商品名、症状、キーワードを入力してください。"/>
    <div class="search_icon pattern1"><input id="search_icon" type="button" value="" /></div>
</form>
<script src="/js/searchClear/addclear.min.js"></script>
<script>
    $(function () {
        $( ".item-search" ).addClear({
            top: '5px',
            right: '16px',
            paddingRight: '36px',
            showOnLoad: true,
        });
        
        $( ".item-search" ).autocomplete({
            source: function(request, response) {
                var searchData = {name: request.term};
                if ($("#search_form").length) {
                    var searchData = $("#search_form").serializeArray();
                    for(var i=0; i<searchData.length; i++) {
                        var item = searchData[i];
                        if (item.name == 'name') {
                            item.value = request.term;
                        }
                    }
                }                
                
                $.ajax({
                    url: "{{ route('search-item.search') }}",
                    data: searchData,
                    type: "post",
                    success: function(data){
                        response(data);
                    }
                });
            },
            minLength: 0,
        }).on('focus', function() { $(this).keydown(); });

        $('.item-search').keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                event.preventDefault();
                search_items($(this).val());
            }
        });

        $("#search_icon").click(function() {
            let keyword = $(this).parent().parent().find("input.item-search").val();
            search_items(keyword);
        });

        function search_items(keyword) {
            if ($("#search_form").length) {
                $("#search_name").val(keyword);
                $("#search_form").submit();
            } else {
                $("#header_search_form").submit();
            }
            
        }
    });
</script>