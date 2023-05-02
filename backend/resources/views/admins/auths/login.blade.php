@extends('admins.layouts.app')

@section('title', 'ログイン')

@section('content')
    <div class="container card col-lg-6 col-md-8">
        <div class="card-header">
            <h1 class="">ログイン</h1>
        </div>
        <div class="card-body">
            @if (session('message'))
            <div class="alert alert-primary">
                <p>{{ session('message') }}</p>
            </div>
            @endif
            @if($errors->get('login'))
            <div class="alert alert-danger">
            @foreach ($errors->get('login') as $error)
                <p>{{ $error }}</p>
            @endforeach
            </div>
            @endif
            <form method="POST" action="{{ route('admin.auth.login') }}" class="form">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="login_id">ログインID</label>
                    <input type="login_id" name="login_id" id="login_id" class="form-control" value="{{ old('login_id') }}">
                    @foreach ($errors->get('login_id') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </div>
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" name="password" id="password" class="form-control">
                    @foreach ($errors->get('password') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </div>
                <button type="submit" name="login" class="btn btn-primary">ログイン</button>
                {{--<a href="{{ route('admin.auth.forgot-password') }}" class="text-primary">パスワードを忘れた方はこちら</a>--}}
            </form>
        </div>
    </div>
@endsection
