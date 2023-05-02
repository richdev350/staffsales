@extends('admins.layouts.app')
@section('title', 'ユーザ詳細 | ユーザ管理')
@section('content')
    <h1>ユーザ - 詳細</h1>
    <div class="card p-5">
        <dl class="form-group row">
            <dt class="col-2">名前</dt>
            <dd class="col-10">
                {{ old('name') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">ログインID</dt>
            <dd class="col-10">
                {{ old('login_id') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">メールアドレス</dt>
            <dd class="col-10">
                {{ old('email') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">パスワード</dt>
            <dd class="col-10">
                {{ str_repeat('*', strlen(old('password'))) }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">権限</dt>
            <dd class="col-10">
                {{ old('role') }}
            </dd>
        </dl>
        <div class="card-footer">
            @include('admins.components.buttons.edit', ['url' => route('admin.admin-user.edit', $id)])
            @if($login_admin_user->id != $id)
            <form action="{{ route('admin.admin-user.destroy', $id) }}" method="post" class="d-inline">
                {{ method_field('delete') }}
                {{ csrf_field() }}
                @include('admins.components.buttons.delete', ['name' => old('name')])
            </form>
            @endif
            @include('admins.components.buttons.back_list', ['url' => url('admin/admin-user/list')])
        </div>
</div>
@endsection
