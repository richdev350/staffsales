@extends('admins.layouts.app')
@php
    $isEdit = false;
    if (stristr(current_route_name(), 'edit') || stristr(current_route_name(), 'update')) {
        $isEdit = true;
    }
@endphp
@section('title', 'ユーザ'.($isEdit ? '編集' : '登録').' | ユーザ管理')

@section('content')

    <h1>ユーザ - {{ $isEdit ? '編集' : '登録' }}</h1>
    <div class="card p-5">
        <form
            @if ($isEdit)
                action="{{ route('admin.admin-user.update', ['id' => $id]) }}"
            @else
                action="{{ route('admin.admin-user.store') }}"
            @endif
            method="post" class="register-form">
            @if ($isEdit)
                {{ method_field('patch') }}
            @endif
            {{ csrf_field() }}
            <dl class="form-group row">
                <dt class="col-2">名前</dt>
                <dd class="col-10">
                    <input name="name" type="text" class="form-control" value="{{ old('name') }}">
                    @foreach ($errors->get('name') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">ログインID</dt>
                <dd class="col-10">
                    <input name="login_id" type="text" class="form-control" placeholder="" value="{{ old('login_id') }}">
                    @foreach ($errors->get('login_id') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">メールアドレス</dt>
                <dd class="col-10">
                    <input name="email" type="text" class="form-control" placeholder="" value="{{ old('email') }}">
                    @foreach ($errors->get('email') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">パスワード</dt>
                <dd class="col-10">
                    <input name="password" type="password" class="form-control" placeholder="変更する場合のみ入力してください" value="{{ old('password') }}">
                    @foreach ($errors->get('password') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">権限</dt>
                <dd class="col-10">
                    <span class="settings-select">
                        <select name="role" class="form-control">
                            <option value=""></option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role')==$role->name?"selected":"" }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </span>
                    @foreach ($errors->get('role') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <div class="card-footer">
                @include('admins.components.buttons.confirm')
                @include('admins.components.buttons.back_list', ['url' => url('admin/admin-user/list')])
            </div>

        </form>
    </div>
@endsection
