@extends('admins.layouts.app')

@section('content')
    <div class="mb-2">
        <h1>権限がありません</h1>
    </div>
    <a class="btn" href="{{ route('admin.home') }}">
        ホームへ戻る
    </a>
@endsection
