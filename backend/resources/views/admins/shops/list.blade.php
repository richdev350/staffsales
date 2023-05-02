@extends('admins.layouts.app')

@section('title', '店舗一覧 | 店舗管理')
<?php
use App\Services\Admin\AuthenticateAdminUserService;
$login_user = AuthenticateAdminUserService::getAuthenticatedUserEntity();
?>
@section('content')
    <div class="mb-2">
        <h1>店舗一覧</h1>
    </div>

    <div class="card">
        <form action="{{ route('admin.shop.select') }}" method="post" class="js-search-form">
            {{ csrf_field() }}
            <div class="card-body">
                @include('admins.components.labels.search-title')
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">コード</label>
                    <div class="col-sm-10">
                        <input type="text" name="code" class="form-control" value="{{ isset($conditions['code'])?$conditions['code']:'' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">店舗名</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" value="{{ isset($conditions['name'])?$conditions['name']:'' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">地域</label>
                    <div class="col-sm-4">
                        <select name="region_id" class="form-control">
                            <option value=""></option>
                            @foreach ($regions as $region)
                            <option value="{{ $region->id }}" {{ isset($conditions['region_id'])&&$conditions['region_id']==$region->id?"selected":"" }}>{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="col-sm-2 col-form-label">都道府県</label>
                    <div class="col-sm-4">
                        <select name="prefecture_id" class="form-control">
                            <option value=""></option>
                            @foreach ($prefectures as $pref)
                            <option value="{{ $pref->id }}" {{ isset($conditions['prefecture_id'])&&$conditions['prefecture_id']==$pref->id?"selected":"" }}>{{ $pref->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">市区町村</label>
                    <div class="col-sm-10">
                        <input type="text" name="city" class="form-control" value="{{ isset($conditions['city'])?$conditions['city']:'' }}">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                @include('admins.components.buttons.search')
                @include('admins.components.buttons.clear_condition')
            </div>
        </form>
    </div>
    <div class="mb-2 mt-2">
        @include('admins.components.buttons.add', ['url' => route('admin.shop.create')])
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>コード</th>
                <th>店舗名</th>
                <th>郵便番号</th>
                <th>都道府県</th>
                <th>市区町村</th>
                <th>住所</th>
                <th>電話番号</th>
                <th class="list-table__thead__heading"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paginator as $shop)
            <tr>
                <td>
                    {{ $shop->code }}
                </td>
                <td>
                    {{ $shop->name }}
                </td>
                <td>
                    {{ $shop->zip_code }}
                </td>
                <td>
                    {{ $shop->prefecture?$shop->prefecture->name:'' }}
                </td>
                <td>
                    {{ $shop->city }}
                </td>
                <td>
                    {{ $shop->address }}
                </td>
                <td>
                    {{ $shop->tel }}
                </td>
                <td>
                    @include('admins.components.buttons.show', ['url' => route('admin.shop.show', $shop->id)])
                    @include('admins.components.buttons.edit', ['url' => route('admin.shop.edit', $shop->id)])
                    @include('admins.components.buttons.ignore-item', ['url' => route('admin.ignore-item.list', $shop->id)])
                    <form action="{{ route('admin.shop.destroy', $shop->id) }}" method="post" class="d-inline">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                        @include('admins.components.buttons.delete', ['name' => $shop->name])
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @include('admins.components.pagination', ['paginator' => $paginator, 'position' => 'bottom'])

@endsection
