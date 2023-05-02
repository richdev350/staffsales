<div style="display: none;">
<section id="inline-content_category">
    <div class="content_inner">
        <ul class="child">
            {!! category_tree($categories) !!}
        </ul>
    </div>
    <!-- content_inner -->
</section>
<script>
    $(document).ready(function(){
        $(".category-link").click(function() {
            var category_id = $(this).attr("data");
            $("#search_category_id").val(category_id);
            $("#search_maker_id").val("");
            $("#search_form").submit();
        });
    });
</script>
</div>
