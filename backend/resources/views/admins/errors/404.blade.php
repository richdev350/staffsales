@extends('admins.layouts.app')

@section('content')
    <div class="mb-2">
        <h1>ページが見つかりません</h1>
    </div>
    <a class="btn" href="{{ url('/admin/home') }}">
        ホームへ戻る
    </a>
@endsection
