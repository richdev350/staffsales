@php $list_counts = [10, 30, 50, 100, 200]; @endphp

<div class="form-inline ml-auto d-block">
    <select class="form-control select-count">
        @foreach($list_counts as $count)
            <option value={{$count}} {{ isset($conditions['count'])&&$conditions['count']==$count?"selected":"" }}>{{$count}}</option>
        @endforeach
    </select>
    &nbsp;件表示
</div>