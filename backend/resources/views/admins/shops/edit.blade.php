@extends('admins.layouts.app')
@php
    $isEdit = false;
    if (stristr(current_route_name(), 'edit') || stristr(current_route_name(), 'update')) {
        $isEdit = true;
    }
@endphp

@section('title', '店舗'.($isEdit ? '編集' : '登録').' | 店舗管理')

@section('content')

    <h1>店舗 - {{ $isEdit ? '編集' : '登録' }}</h1>
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
                    <input name="code" type="text" class="form-control" value="{{ old('code') }}">
                    @foreach ($errors->get('code') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">店舗名</dt>
                <dd class="col-10">
                    <input name="name" type="text" class="form-control" value="{{ old('name') }}">
                    @foreach ($errors->get('name') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">郵便番号</dt>
                <dd class="col-10">
                    <input name="zip_code" type="text" class="form-control" placeholder="" value="{{ old('zip_code') }}">
                    @foreach ($errors->get('zip_code') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">都道府県</dt>
                <dd class="col-10">
                    <span class="settings-select">
                        <select name="prefecture_id" class="form-control">
                            <option value=""></option>
                            @foreach ($prefectures as $pref)
                            <option value="{{ $pref->id }}" {{ old('prefecture_id')==$pref->id?"selected":"" }}>{{ $pref->name }}</option>
                            @endforeach
                        </select>
                    </span>
                    @foreach ($errors->get('prefecture_id') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">市区町村</dt>
                <dd class="col-10">
                    <input name="city" type="text" class="form-control" placeholder="" value="{{ old('city') }}">
                    @foreach ($errors->get('city') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">住所</dt>
                <dd class="col-10">
                    <input name="address" type="text" class="form-control" placeholder="" value="{{ old('address') }}">
                    @foreach ($errors->get('address') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">電話番号</dt>
                <dd class="col-10">
                    <input name="tel" type="text" class="form-control" placeholder="" value="{{ old('tel') }}">
                    @foreach ($errors->get('tel') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">管理ユーザ</dt>
                <dd class="col-10">
                    <span class="settings-select">
                        <select name="manager_id" class="form-control">
                            <option value=""></option>
                            @foreach ($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id')==$manager->id?"selected":"" }}>{{ $manager->name }}</option>
                            @endforeach
                        </select>
                    </span>
                    @foreach ($errors->get('manager_id') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">店舗ユーザ</dt>
                <dd class="col-10">
                    <span class="settings-select">
                        <select name="staff_id" class="form-control">
                            <option value=""></option>
                            @foreach ($staffs as $staff)
                            <option value="{{ $staff->id }}" {{ old('staff_id')==$staff->id?"selected":"" }}>{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </span>
                    @foreach ($errors->get('staff_id') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <div class="card-footer">
                @include('admins.components.buttons.confirm')
                @include('admins.components.buttons.back_list', ['url' => url('admin/shop/list')])
            </div>
        </form>
    </div>
@endsection
