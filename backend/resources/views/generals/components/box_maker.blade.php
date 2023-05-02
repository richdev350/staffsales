<div style="display: none;">
    <section id="inline-content_maker">
        <div class="content_inner">
        <ul class="child">
        @foreach($makers as $maker)
        <li>
            <p><a href="javascript:void(0);" data="{{$maker->id}}" title="{{$maker->name}}" class="maker-link">{{$maker->name}}({{ items_count_of_maker($maker) }})</a></p>
        </li>
        @endforeach
    </ul>
    </div>
    <!-- content_inner -->
</section>
</div>
<script>
    $(document).ready(function(){
        $(".maker-link").click(function() {
            var maker_id = $(this).attr("data");
            $("#search_maker_id").val(maker_id);
            $("#search_category_id").val("");
            $("#search_form").submit();
        });
    });
</script>