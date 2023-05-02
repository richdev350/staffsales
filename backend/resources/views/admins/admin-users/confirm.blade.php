@extends('admins.layouts.app')
@php
  $isEdit = false;
  if (stristr(current_route_name(), 'edit') || stristr(current_route_name(), 'update')) {
    $isEdit = true;
  }
@endphp
@section('title', 'ユーザ'.($isEdit ? '編集' : '登録').'確認 | ユーザ管理')

@section('content')

    <h1>ユーザ - {{ $isEdit ? '編集' : '登録' }}確認</h1>
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
                    {{ old('name') }}
                    <input name="name" type="hidden" value="{{ old('name') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">ログインID</dt>
                <dd class="col-10">
                    {{ old('login_id') }}
                    <input name="login_id" type="hidden" value="{{ old('login_id') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">メールアドレス</dt>
                <dd class="col-10">
                    {{ old('email') }}
                    <input name="email" type="hidden" value="{{ old('email') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">パスワード</dt>
                <dd class="col-10">
                    @if ($isEdit && ! old('password'))
                        ※変更無し
                    @else
                        {{ str_repeat('*', strlen(old('password'))) }}
                    @endif
                    <input name="password" type="hidden" value="{{ old('password') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">権限</dt>
                <dd class="col-10">
                    {{ old('role') }}
                    <input name="role" type="hidden" value="{{ old('role') }}">
                </dd>
            </dl>
            <div class="card-footer">
                @include('admins.components.buttons.regist')
                @include('admins.components.buttons.back')
            </div>

        </form>
    </div>
@endsection
