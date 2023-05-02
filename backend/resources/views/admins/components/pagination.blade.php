<?php /* このファイルはUTF-8のBOMなし(UTF-8N)で保存しています */
    $show_count_list = !isset($count_list) || (isset($count_list) && $count_list);
?>
<div class="d-flex justify-content-center">
    <div class="{{ $show_count_list ? 'pagination-frame' : '' }} text-center">
        @if ($paginator->hasPages())
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                @php
                    $paginator->setPath('');
                    $currentPage = $paginator->currentPage();
                    $pageCount   = 3;
                    $pageRange   = pagination_slide_range(range(1, $paginator->lastPage()), $currentPage, $pageCount);
                    $firstPage   = reset($pageRange);
                    $lastPage    = end($pageRange);
                @endphp

                <li class="page-item{{ $paginator->onFirstPage()?' disabled':'' }}">
                    <a href="{{ $paginator->url(1) }}" class="page-link" aria-label="First" title="最初のページ"><i class="fas fa-angle-double-left"></i></a>
                </li>
                <li class="page-item{{ (! $paginator->previousPageUrl())?' disabled':'' }}">
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-link" aria-label="Previous" title="前のページ"><i class="fas fa-angle-left"></i></a>
                </li>
                @if($firstPage != 1)
                    <li class="page-item disabled">
                        <a class="page-link">...</a>
                    </li>
                @endif
                @foreach ($pageRange as $page)
                    <li class="page-item{{ ($currentPage == $page)?' active':'' }}">
                        <a href="{{ $paginator->url($page) }}" class="page-link" title="{{ sprintf('%sページ目', $page) }}">{{ $page }}</a>
                    </li>
                @endforeach

                @if($lastPage != $paginator->lastPage())
                    <li class="page-item disabled">
                        <a class="page-link">...</a>
                    </li>
                @endif

                <li class="page-item{{ (! $paginator->nextPageUrl())?' disabled':'' }}">
                    <a href="{{ $paginator->nextPageUrl() }}" class="page-link" aria-label="Next" title="次のページ"><i class="fas fa-angle-right"></i></a>
                </li>
                <li class="page-item{{ ($currentPage == $paginator->lastPage())?' disabled':'' }}">
                    <a href="{{ $paginator->url($paginator->lastPage()) }}" class="page-link" aria-label="Last" title="最後のページ"><i class="fas fa-angle-double-right"></i></a>
                </li>
            </ul>
        </nav>
        @endif
        <p class="pagination-undertext">{{ sprintf('全 %s 件　[ %s ～ %s 件目 ]', number_format($paginator->total()), number_format($paginator->firstItem()), number_format($paginator->lastItem())) }}</p>
    </div>
    @if (isset($conditions) && isset($conditions['count']) && $show_count_list)
        <input type="hidden" id="count-per-page" value="{{ $conditions['count'] }}">
        @include('admins.components.count_list')
    @endif
</div>

<script>
    $(function(){
        let form = $(".js-search-form");
        if ($('input#count-per-page').length > 0) {
            $('input#count-per-page').attr({ name: "count"}).appendTo(form);
        }

        $('.select-count').change(function() {
            let count = $(this).val();

            if ($('input[type=hidden][name=count]').val() != count) {
                $('input[type=hidden][name=count]').val(count);
                form.submit();
            }
        });
    });
</script>