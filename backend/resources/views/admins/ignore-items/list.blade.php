@extends('admins.layouts.app')
@php
$ignore_ids = $shop->ignore_items->pluck('id')->all();
@endphp
@section('title', $shop->name.' 取扱商品一覧 | 取扱商品管理')
@section('content')
    <div class="mb-2">
        <h1>{{$shop->name}} 取扱商品一覧</h1>
    </div>
    <div class="mb-2 mt-2">
        <form action="{{ url('/admin/ignore-item/batch/' . $shop->id) }}" method="post" class="form-inline" id="batch_form">
            {{ csrf_field() }}
            <input type="hidden" id="targets" name="targets"/>
            <select id="batch_action" name="action" class="form-control mr-1">
                <option value=""></option>
                <option value="ignore">チェックしたものを非取扱商品にする</option>
            </select>
            @include('admins.components.buttons.batch-ignore')
        </form>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>取扱無し</th>
                <th>表示</th>
                <th>JAN</th>
                <th>商品名</th>
                <th>価格</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
            <tr data-id="{{ $item->id }}">
                <td>
                    <input type="checkbox" class="batch-checkbox" value="{{ $item->id }}"{{ in_array($item->id, $ignore_ids)?' checked':'' }}>
                </td>
                <td>
                    {{ $item->is_visible?'表示':'非表示' }}
                </td>
                <td>
                    {{ $item->jan }}
                </td>
                <td>
                    {{ $item->name }}
                </td>
                <td>
                    {{ number_format($item->price) }}円
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script>
    $("#batch_ignore_btn").click(function() {
        var targets = $(".batch-checkbox:checked").map(function(){
            return $(this).val();
        }).get();

        if (!$("#batch_action").val()) {
            alert("一括処理操作を選択してください。")
        } else {
            $("#targets").val(targets.join());
            $("#batch_form").submit();
        }

    });
    </script>
@endsection
