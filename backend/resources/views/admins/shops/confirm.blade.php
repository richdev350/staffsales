@extends('admins.layouts.app')
@php
  $isEdit = false;
  if (stristr(current_route_name(), 'edit') || stristr(current_route_name(), 'update')) {
    $isEdit = true;
  }
@endphp
@section('title', '店舗'.($isEdit ? '編集' : '登録').'確認 | 店舗管理')
@section('content')
    <h1>店舗 - {{ $isEdit ? '編集' : '登録' }}確認</h1>
    <div class="card p-5">
        <form
            @if ($isEdit)
                action="{{ route('admin.shop.update', ['id' => $id]) }}"
            @else
                action="{{ route('admin.shop.store') }}"
            @endif
            method="post" class="register-form">
            @if ($isEdit)
                {{ method_field('patch') }}
            @endif
            {{ csrf_field() }}
            <dl class="form-group row">
                <dt class="col-2">コード</dt>
                <dd class="col-10">
                    {{ old('code') }}
                    <input name="code" type="hidden" value="{{ old('code') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">店舗名</dt>
                <dd class="col-10">
                    {{ old('name') }}
                    <input name="name" type="hidden" value="{{ old('name') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">郵便番号</dt>
                <dd class="col-10">
                    {{ old('zip_code') }}
                    <input name="zip_code" type="hidden" value="{{ old('zip_code') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">都道府県</dt>
                <dd class="col-10">
                    {{ EntityAdaptor::getPrefectureName(old('prefecture_id')) }}
                    <input name="prefecture_id" type="hidden" value="{{ old('prefecture_id') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">市区町村</dt>
                <dd class="col-10">
                    {{ old('city') }}
                    <input name="city" type="hidden" value="{{ old('city') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">住所</dt>
                <dd class="col-10">
                    {{ old('address') }}
                    <input name="address" type="hidden" value="{{ old('address') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">電話番号</dt>
                <dd class="col-10">
                    {{ old('tel') }}
                    <input name="tel" type="hidden" value="{{ old('tel') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">管理ユーザ</dt>
                <dd class="col-10">
                    {{ EntityAdaptor::getAdminUserName(old('manager_id')) }}
                    <input name="manager_id" type="hidden" value="{{ old('manager_id') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">店舗ユーザ</dt>
                <dd class="col-10">
                    {{ EntityAdaptor::getAdminUserName(old('staff_id')) }}
                    <input name="staff_id" type="hidden" value="{{ old('staff_id') }}">
                </dd>
            </dl>
            <div class="card-footer">
                @include('admins.components.buttons.regist')
                @include('admins.components.buttons.back')
            </div>
        </form>
    </div>
@endsection
