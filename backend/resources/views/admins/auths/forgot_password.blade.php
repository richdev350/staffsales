@extends('admins.layouts.app')

@section('title', 'パスワードを忘れた方')

@section('content')

    <div class="card col-6">
        <div class="card-header">
            <h1 class="">パスワードを忘れた方</h1>
        </div>
        <div class="card-body">
            @if (session('message'))
            <div class="alert alert-primary">
                <p>{{ session('message') }}</p>
            </div>
            @endif
            <form method="POST" action="{{ route('admin.auth.request-password') }}" class="form">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                    @foreach ($errors->get('email') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </div>
                <button type="submit" name="login" class="btn btn-primary">再設定メール送信</button>
            </form>
        </div>
    </div>
@endsection
