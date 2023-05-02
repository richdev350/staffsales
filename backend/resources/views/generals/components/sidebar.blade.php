@include('generals.components.sidebar_category')
<form id="search_form" action="{{ route('item.select') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" id="search_sort" name="sort" value="{{ isset($conditions['sort'])?$conditions['sort']:'' }}"/>
    <input type="hidden" id="search_count" name="count" value="{{ isset($conditions['count'])?$conditions['count']:'' }}"/>
    <input type="hidden" id="search_name" name="name" value="{{ isset($conditions['name'])?$conditions['name']:'' }}"/>
    <input type="hidden" id="search_category_id" name="item_category_id" value="{{ isset($conditions['item_category_id'])?$conditions['item_category_id']:'' }}"/>
    <input type="hidden" id="search_maker_id" name="maker_id" value="{{ isset($conditions['maker_id'])?$conditions['maker_id']:'' }}"/>
    <input type="hidden" id="search_label" name="label" value="{{ isset($conditions['label'])?$conditions['label']:'' }}"/>
    {{-- @include('generals.components.sidebar_maker') --}}
    @include('generals.components.sidebar_label')
    @include('generals.components.sidebar_price')
</form>

<script>
    $(function () {
        $( "#search_form input" ).change(function() {
            $("#search_form").submit();
        });

        $("#clear_price").click(function() {
            $(".search-price input").prop("checked", false);
            $("#search_form").submit();
        });

        $("#clear_labels").click(function() {
            $(".search-labels input").prop("checked", false);
            $("#search_form").submit();
        });

        $("#clear_makers").click(function() {
            $(".search-makers input").prop("checked", false);
            $("#search_form").submit();
        });
    });
</script>