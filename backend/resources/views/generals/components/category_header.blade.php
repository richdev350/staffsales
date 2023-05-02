<div id="menuwrap">
    <section>
        <ul id="breadcrumbs-four" class="cf">
            <li class="labels"><a href="{{ config('app.root_path') }}#"><span class="pc">商品カテゴリから商品を探す</span><span class="tb">商品を探す</span></a></li>
            @foreach($categories as $category)
                @php if ($category->items_count == 0) continue; @endphp
                <li class="level1 toggle">
                    <span class="pc_catemenu1">
                        <a href="{{ route('item.list', ['item_category_id' =>$category->id]) }}" class="pc_catemenu{{$category->id}}">{{$category->name}}</a>
                    </span>
                    @if(!empty($category->children))
                        <div class="menu_area clearfix submenu01">
                            <div class="in">
                                <ul class="child_pcmenu1 clearfix">
                                    @foreach($category->children as $sub_category)
                                        @php if ($sub_category->items_count == 0) continue; @endphp
                                        <li class="level2">
                                        <span class="menu{{$sub_category->id}}">
                                            <a href="{{ route('item.list', ['item_category_id' => $sub_category->id]) }}" class="list{{$sub_category->id}}">{{$sub_category->name}}</a>
                                        </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </section>
</div><!--menuwrap-->