@extends('admins.layouts.app')

@section('title', 'パスワード再設定')

@section('content')

    <div class="card col-6">
        <div class="card-header">
            <h1 class="">パスワード再設定</h1>
        </div>
        <div class="card-body">
            @if (session('message'))
            <div class="alert alert-primary">
                <p>{{ session('message') }}</p>
            </div>
            @endif
            <form method="POST" action="{{ route('admin.auth.reset-password', ['token' => $token]) }}" class="form">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                    @foreach ($errors->get('email') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </div>
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" name="password" id="password" value="{{ old('password') }}" class="form-control">
                    @foreach ($errors->get('password') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </div>
                <div class="form-group">
                    <label for="password_confirmation">パスワード確認</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control">
                    @foreach ($errors->get('password_confirmation') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </div>
                <button type="submit" name="login" class="btn btn-primary">パスワード再設定</button>
            </form>
        </div>
    </div>
@endsection
