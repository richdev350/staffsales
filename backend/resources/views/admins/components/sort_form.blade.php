    @if ($paginator->hasPages())
    <input type="hidden" name="page" value="{{ $paginator->currentPage() }}" id="js-current-page" />
    @else
    <input type="hidden" name="page" value="" id="js-current-page" />
    @endif
    <form action="{{ route($root.'.sort') }}" method="post" id="js-sort-form">
        {{ csrf_field() }}
        @if(isset($conditions))
            @foreach($conditions as $name => $value)
                @if (is_array($value))
                    @foreach($value as $k => $val)
                        <input type="hidden" name="conditions[{{ $name }}][]" value="{{ $val }}" />
                    @endforeach
                @else
                    @if($name != 'orders' && $name != 'orderby' && $name != 'orderByRaws')
                        <input type="hidden" name="conditions[{{ $name }}]" value="{{ $value }}" />
                    @endif
                @endif
                
            @endforeach
        @endif
    </form>
    <form action="{{ route($root.'.sort_exchange') }}" method="post" id="js-sort-exchange-form">
        {{ csrf_field() }}
        @if(isset($conditions))
            @foreach($conditions as $name => $value)
                @if (is_array($value))
                @foreach($value as $k => $val)
                    <input type="hidden" name="conditions[{{ $name }}][]" value="{{ $val }}" />
                @endforeach
                @else
                    @if($name != 'orders' && $name != 'orderby' && $name != 'orderByRaws')
                        <input type="hidden" name="conditions[{{ $name }}]" value="{{ $value }}" />
                    @endif
                @endif

                
            @endforeach
        @endif
    </form>
