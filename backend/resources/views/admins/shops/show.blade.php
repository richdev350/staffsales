@extends('admins.layouts.app')
@section('title', '店舗詳細 | 店舗管理')
@section('content')
    <h1>店舗 - 詳細</h1>
    <div class="card p-5">
        <dl class="form-group row">
            <dt class="col-2">コード</dt>
            <dd class="col-10">
                {{ old('code') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">店舗名</dt>
            <dd class="col-10">
                {{ old('name') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">郵便番号</dt>
            <dd class="col-10">
                {{ old('zip_code') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">地域</dt>
            <dd class="col-10">
                {{ old('region_name') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">都道府県</dt>
            <dd class="col-10">
                {{ EntityAdaptor::getPrefectureName(old('prefecture_id')) }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">市区町村</dt>
            <dd class="col-10">
                {{ old('city') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">住所</dt>
            <dd class="col-10">
                {{ old('address') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">電話番号</dt>
            <dd class="col-10">
                {{ old('tel') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">管理ユーザ</dt>
            <dd class="col-10">
                {{ EntityAdaptor::getAdminUserName(old('manager_id')) }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">店舗ユーザ</dt>
            <dd class="col-10">
                {{ EntityAdaptor::getAdminUserName(old('staff_id')) }}
            </dd>
        </dl>
        <div class="card-footer">
            @include('admins.components.buttons.edit', ['url' => route('admin.shop.edit', $id)])
            <form action="{{ route('admin.shop.destroy', $id) }}" method="post" class="d-inline">
                {{ method_field('delete') }}
                {{ csrf_field() }}
                @include('admins.components.buttons.delete', ['name' => old('name')])
            </form>
            @include('admins.components.buttons.back_list', ['url' => url('admin/shop')])
        </div>
</div>
@endsection
