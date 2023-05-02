<?php /* このファイルはUTF-8のBOMなし(UTF-8N)で保存しています */ ?>
<div class="pager_navi pc">
    @if ($paginator->hasPages())
        @php
            $paginator->setPath('');
            $currentPage = $paginator->currentPage();
            $pageCount   = 5;
            $pageRange   = pagination_slide_range(range(1, $paginator->lastPage()), $currentPage, $pageCount);
            $firstPage   = reset($pageRange);
            $lastPage    = end($pageRange);
        @endphp

        @if ($paginator->previousPageUrl())
        <a href="{{ $paginator->previousPageUrl() }}">{{ '<前へ' }}</a>
        @endif
        @foreach ($pageRange as $page)
            @if ($currentPage == $page)
                <strong>{{ $page }}</strong>
            @else
                <a href="{{ $paginator->url($page) }}">{{ $page }}</a>
            @endif

        @endforeach

        @if ($paginator->nextPageUrl())
        <a href="{{ $paginator->nextPageUrl() }}">次へ></a>
        @endif
    @endif

</div><!--navi-->

<div class="pager_navi sp">
    <div class="inner cf">
        @if ($paginator->hasPages())
            @php
                $paginator->setPath('');
                $currentPage = $paginator->currentPage();
                $pageCount   = 1;
                $pageRange   = pagination_slide_range(range(1, $paginator->lastPage()), $currentPage, $pageCount);
                $firstPage   = reset($pageRange);
                $lastPage    = end($pageRange);
            @endphp

            @if ($paginator->previousPageUrl())
            <a href="{{ $paginator->previousPageUrl() }}">{{ '<前へ' }}</a>
            @endif

            @php
                if(1 == $paginator->currentPage()){
                    $count_start = 1;
                    $count_end = count($paginator);
                }else{
                    $count_start = ($paginator->currentPage() - 1)*intval($conditions['count']);
                    $count_end = ($paginator->currentPage() - 1)*intval($conditions['count']) + count($paginator);
                }
            @endphp
            <strong>{{$count_start}}-{{$count_end}}/{{number_format($paginator->total())}}件</strong>

            @if ($paginator->nextPageUrl())
            <a href="{{ $paginator->nextPageUrl() }}">次へ></a>
            @endif
        @endif
    </div>
</div><!--navi-->