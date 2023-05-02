<a href="{{ route('item.detail', ['item_category_id' => $item->item_categories[0]->id, 'id' => $item->id]) }}">
    @if ( $item->is_viewing_restriction && (!$session_age_limit || $session_age_limit < $item->age_limit))
        <div class="age-limit">閲覧制限</div>
    @else
        @if (!($item->files->isEmpty()))
            <img src="{{ $item->files[0]->url }}" alt="{{$item->name}}" class="photo"/>
        @else
            <img src="/img/no-image.png"/>
        @endif
    @endif
</a>