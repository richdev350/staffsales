@extends('admins.layouts.app')

@section('title', '商品一覧 | 商品管理')
@section('content')
<link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css">
<link rel="stylesheet" href="{{ asset('js/comboTree/style.css')}}">
<script type="text/javascript" src="{{ asset('js/comboTree/comboTreePlugin.js') }}"></script>
    <div class="mb-2">
        <h1>商品一覧</h1>
    </div>
    <div class="card">
        <form action="{{ route('admin.item.select') }}" method="post" class="js-search-form">
            {{ csrf_field() }}
            <div class="card-body">
                @include('admins.components.labels.search-title')
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">JAN/商品名</label>
                    <div class="col-sm-10">
                        <input type="text" name="text" class="form-control" value="{{ isset($conditions['text'])?$conditions['text']:'' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">メーカー</label>
                    <div class="col-sm-10">
                        <select name="maker_id" class="form-control">
                            <option value=""></option>
                            @foreach ($makers as $maker)
                                <option value="{{ $maker->id }}" {{ isset($conditions['maker_id'])&&$conditions['maker_id']==$maker->id?"selected":"" }}>{{ $maker->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">商品カテゴリ</label>
                    <div class="col-sm-10">
                        <input type="text" id="item_category_name" class="form-control" placeholder="商品カテゴリ検索"/>
                        <input type="hidden" id="item_category_id" name="item_category_id" value="{{ isset($conditions['item_category_id'])?$conditions['item_category_id']:'' }}"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">表示</label>
                    <div class="col-sm-10">
                        <div class="form-inline" style="margin-top: .5rem;">
                            <input id="check-visible" name="is_visibles[]" type="checkbox" class="form-check-input" value="1" @if(isset($conditions['is_visibles']) && in_array(1, (array)$conditions['is_visibles'])) checked @endif>
                            <label for="check-visible" class="form-check-label mr-2">表示</label>
                            <input id="check-invisible" name="is_visibles[]" type="checkbox" class="form-check-input" value="0" @if(isset($conditions['is_visibles']) && in_array(0, (array)$conditions['is_visibles'])) checked @endif>
                            <label for="check-invisible" class="form-check-label mr-2">非表示</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                @include('admins.components.buttons.search')
                @include('admins.components.buttons.clear_condition')
                @include('admins.components.buttons.csv_download')
            </div>
        </form>
    </div>
    <div class="mb-2 mt-2">
        @include('admins.components.import_form', ['url' => route('admin.item.import')])
    </div>
    <div class="mb-2 mt-2">
        <form action="{{ route('admin.item.batch') }}" method="post" class="form-inline" id="batch_form">
            {{ csrf_field() }}
            <input type="hidden" id="targets" name="targets"/>
            <select id="batch_action" name="action" class="form-control mr-1">
                <option value=""></option>
                <option value="show">チェックしたものを表示</option>
                <option value="hide">チェックしたものを非表示</option>
            </select>
            @include('admins.components.buttons.batch')
            <div class="ml-auto">
                @include('admins.components.buttons.add', ['url' => route('admin.item.create')])
            </div>
        </form>
    </div>
    @include('admins.components.buttons.sort_run')
    <table class="table table-striped">
        <thead>
            <tr>
                @include('admins.components.buttons.batch_column_header', ['all_check' => $paginator->count()])
                <th>表示</th>
                <th colspan="2">ソート</th>
                <th>JAN</th>
                <th>商品名</th>
                <th>価格</th>
                <th class="list-table__thead__heading">操作</th>
            </tr>
        </thead>
        <tbody class="js-sortable">
            @foreach ($paginator as $item)
            <tr data-id="{{ $item->id }}">
                <td>
                    <input type="checkbox" class="batch-checkbox" value="{{ $item->id }}">
                </td>
                <td>
                    {{ $item->is_visible?'表示':'非表示' }}
                </td>
                <td>
                    <button class="btn btn-outline-secondary js-sort-up" data-id="{{ $item->id }}">↑</button>
                </td>
                <td>
                    <button class="btn btn-outline-secondary js-sort-down" data-id="{{ $item->id }}">↓</button>
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
                <td class="js-not-sortable">
                    @include('admins.components.buttons.show', ['url' => route('admin.item.show', $item->id)])
                    @include('admins.components.buttons.edit', ['url' => route('admin.item.edit', $item->id)])
                    <form action="{{ route('admin.item.destroy', $item->id) }}" method="post" class="d-inline">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                        @include('admins.components.buttons.delete', ['name' => $item->name])
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @include('admins.components.sort_form', ['root' => 'admin.item'])
    @include('admins.components.pagination', ['paginator' => $paginator, 'position' => 'bottom'])
    <script>
        var selectedCategoryId;
        @isset($conditions['item_category_id'])
        selectedCategoryId = {{ $conditions['item_category_id'] }};
        @endisset

        var treeJsonData = '{{ get_category_tree_data() }}'.replace(/&quot;/g,'"');
        treeJsonData = JSON.parse(treeJsonData);

        $(function(){
            var instance = $('#item_category_name').comboTree({
                source : treeJsonData,
                isMultiple: false
            });

            if (selectedCategoryId) {
                instance.setSelection([selectedCategoryId]);
            }

            $("#item_category_name").change(function() {
                var selectedIds = instance.getSelectedIds();
                $("#item_category_id").val(selectedIds[0]);
            });

        });


    </script>
@endsection
