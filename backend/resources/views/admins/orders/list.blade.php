@extends('admins.layouts.app')

@section('title', '受注管理 | 受注管理')

@section('content')
    <div class="mb-2">
        <h1>受注管理</h1>
    </div>
    <div class="card">
        <form action="{{ route('admin.order.select') }}" method="post" class="js-search-form">
            {{ csrf_field() }}
            <div class="card-body">
                @include('admins.components.labels.search-title')
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">注文番号</label>
                    <div class="col-sm-4">
                        <input type="text" name="id" class="form-control" value="{{ isset($conditions['id'])?$conditions['id']:'' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">注文日</label>
                    <div class="col-sm-4 row">
                        <div class="col-sm-5">
                            <input type="text" name="created_at_from" class="form-control datepicker" value="{{ isset($conditions['created_at_from'])?$conditions['created_at_from']:'' }}">
                        </div>
                        ～
                        <div class="col-sm-5">
                            <input type="text" name="created_at_to" class="form-control datepicker" value="{{ isset($conditions['created_at_to'])?$conditions['created_at_to']:'' }}">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">お名前</label>
                    <div class="col-sm-4">
                        <input type="text" name="name" class="form-control" value="{{ isset($conditions['name'])?$conditions['name']:'' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">社員番号</label>
                    <div class="col-sm-4">
                        <input type="text" name="staff_id" class="form-control" value="{{ isset($conditions['staff_id'])?$conditions['staff_id']:'' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">削除済み</label>
                    <div class="col-sm-4">
                        <input type="checkbox" name="only_trashed" id="only_trashed" value="1" {{ isset($conditions['only_trashed'])?"checked":"" }}>
                        <label for="only_trashed">削除済みのみ表示</label>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                @include('admins.components.buttons.search')
                @include('admins.components.buttons.clear_condition')
                @if($login_admin_user->can('admin_permission'))
                    @include('admins.components.buttons.csv_download')
                @endif
            </div>
        </form>
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>注文番号</th>
            <th>注文日時</th>
            <th>お名前</th>
            <th>社員番号</th>
            <th>購入金額</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($paginator as $order)
            <tr>
                <td>
                    {{ $order->id }}
                </td>
                <td>
                    {{ $order->created_at->format('Y-m-d H:i') }}
                </td>
                <td>
                    {{ $order->name }}
                </td>
                <td>
                    {{ $order->staff_id }}
                </td>
                <td>
                    {{ number_format($order->sum) }}円
                </td>
                <td>
                    @include('admins.components.buttons.show', ['url' => route('admin.order.show', $order->id)])
                    @if(!$order->deleted_at && ($login_admin_user->can('admin_permission')))
                        @include('admins.components.buttons.edit', ['url' => route('admin.order.edit', $order->id)])
                    @endif
                    @if(!$order->deleted_at && ($login_admin_user->can('admin_permission') || $order->state == 'pending'))
                        <form action="{{ route('admin.order.destroy', $order->id) }}" method="post" class="d-inline">
                            {{ method_field('delete') }}
                            {{ csrf_field() }}
                            @include('admins.components.buttons.delete', ['name' => '注文番号：' . $order->id])
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('admins.components.pagination', ['paginator' => $paginator, 'position' => 'bottom'])

@endsection
