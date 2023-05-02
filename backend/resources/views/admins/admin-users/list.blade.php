@extends('admins.layouts.app')

@section('title', 'ユーザ一覧 | ユーザ管理')

@section('content')
    <div class="mb-2">
        <h1>ユーザ一覧</h1>
    </div>

    <div class="card">
        <form action="{{ route('admin.admin-user.select') }}" method="post" class="js-search-form">
            {{ csrf_field() }}
            <div class="card-body">
                @include('admins.components.labels.search-title')
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">名前</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" value="{{ isset($conditions['name'])?$conditions['name']:'' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">メールアドレス</label>
                    <div class="col-sm-10">
                        <input type="text" name="email" class="form-control" value="{{ isset($conditions['email'])?$conditions['email']:'' }}">
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
        @include('admins.components.buttons.add', ['url' => route('admin.admin-user.create')])
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>店舗名（店舗コード）</th>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>権限</th>
                <th class="list-table__thead__heading"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paginator as $admin_user)
            <tr>
                <td>
                    @if(!is_null($admin_user->shops->first()))
                        {{ $admin_user->shops->first()->name }}
                        （{{ $admin_user->shops->first()->code }}）
                    @endif
                </td>
                <td>
                    {{ $admin_user->name }}
                </td>
                <td>
                    {{ $admin_user->email }}
                </td>
                <td>
                    {{ $admin_user->getRoleNames()[0] }}
                </td>
                <td>
                    @include('admins.components.buttons.show', ['url' => route('admin.admin-user.show', $admin_user->id)])
                    @include('admins.components.buttons.edit', ['url' => route('admin.admin-user.edit', $admin_user->id)])
                    @if($login_admin_user->id != $admin_user->id)
                    <form action="{{ route('admin.admin-user.destroy', $admin_user->id) }}" method="post" class="d-inline">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                        @include('admins.components.buttons.delete', ['name' => $admin_user->name])
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @include('admins.components.pagination', ['paginator' => $paginator, 'position' => 'bottom'])

@endsection
