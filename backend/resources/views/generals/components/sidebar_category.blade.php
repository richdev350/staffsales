<div class="left_box">
    <div class="title_bg_pink">商品カテゴリ</div>
    <ul class="side_list">
        @foreach($categories as $category)
            @php if ($category->items_count == 0) continue; @endphp
            <li class="level1">
                <p class="menu parent-category">
                    @if(!empty($category->children))
                        <a href="javascript:void(0);">{{$category->name}}({{ $category->items_count }})</a>
                    @else
                        <a href="{{ route('item.list', ['item_category_id' => $category->id]) }}">{{$category->name}}({{ $category->items_count }})</a>
                    @endif
                </p>
                @if(!empty($category->children))
                    <ul class="child">
                        @foreach($category->children as $sub_category)
                            @php if ($sub_category->items_count == 0) continue; @endphp
                            <li class="level2">
                                <p class="menu38">
                                    <a href="{{ route('item.list', ['item_category_id' => $sub_category->id]) }}" data="{{$sub_category->id}}"  class="category-link">{{$sub_category->name}}({{ $sub_category->items_count }})</a>
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</div>
<script>
    $(document).ready(function(){
        $('.parent-category').click(function() {
            if ($(this).next().hasClass("active")) {
                $(this).next().removeClass("active");
            } else {
                $(this).next().addClass("active");
            }
        });

        $(".category-link").click(function() {
            var category_id = $(this).attr("data");
            $("#search_category_id").val(category_id);
            $("#search_form").submit();
        });
    });
</script>
